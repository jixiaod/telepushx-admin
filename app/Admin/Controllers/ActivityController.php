<?php

namespace App\Admin\Controllers;

use Illuminate\Support\Facades\Log;
use App\Jobs\MutiLang;
use App\Admin\Actions\ActivityEventButton;
use App\Admin\Repositories\Activity;
use App\Models\Activity as ActivityModel;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Widgets\Card;
use Illuminate\Support\Facades\Storage;
use Dcat\Admin\Widgets\Modal;
use App\Admin\Actions\Grid\HdAction;

class ActivityController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Activity(), function (Grid $grid) {
            $grid->model()->orderBy('activity_time','asc');
            $grid->column('id')->sortable();
            $grid->column('activity_text')
                ->expand(function () {
                    $card = new Card(null, $this->activity_text);

                    return "<div style='padding:10px 10px 0'>$card</div>";
                });

            $grid->column('activity_image')->display(function ($pictures) {
            
                return json_decode($pictures, true);
            
            })->image('', 100, 100);
            $grid->column('activity_time')->editable();
            
            //$grid->column('order')->editable();
            $grid->column('status')->switch();
            $grid->column('type')
                ->display(function ($value) {
                    return $value == 1 ? 'Video' : 'Picture';
                });
            $grid->column('is_pin', 'pinned')->switch();
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
            });
            
            $grid->setActionClass(Grid\Displayers\Actions::class);
            $grid->actions(function (Grid\Displayers\Actions $actions)use($grid) {
                
                $actions->append(new HdAction());
            });


        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new Activity(), function (Show $show) {
            $show->field('id');
            $show->field('activity_text');
            $show->field('activity_image');
            $show->field('mp4');
            $show->field('activity_time');
            $show->field('status');
            $show->field('type');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(Activity::with('buttons'), function (Form $form) {

            $form->saving(function (Form $form) {
                // 判断推送时间是否相邻太近，最少间隔20分钟
                $inputActivityTime = $form->activity_time;
                if (empty($inputActivityTime)) return;

                $inputActivityId = $form->model()->id;
                
                // 将输入的时间转换为分钟（包含跨天处理）
                $inputTimeMinutes = convertToMinutes($inputActivityTime);
                $activityList = ActivityModel::select('id', 'activity_time')->where("id", '<>', $inputActivityId)->get()->toArray();
                foreach ($activityList as $act) {
                    // 将数据库中的时间转换为分钟（包含跨天处理）
                    $activityTimeMinutes = convertToMinutes($act['activity_time']);
                    // 允许相同时间，同一时间的推送，随机选择一条发送
                    if ($inputActivityId == $act['id'] || $activityTimeMinutes === $inputTimeMinutes) {
                        continue;
                    }

                    // 计算时间差，取最小的正值间隔（跨天时取绝对时间差）
                    $timeDifference = abs($inputTimeMinutes - $activityTimeMinutes);
                    if ($timeDifference > 720) { // 一天是 1440 分钟，跨天情况
                        $timeDifference = 1440 - $timeDifference;
                    }

                    // 检查时间差是否小于 20 分钟
                    if ($timeDifference < 20) {
                        return $form->response()->error('请保证前后推送间隔时间不少于20分钟');
                    }
                }
            });

            $form->display('id');
            $move = 'picture/' . date('Ymd', time());
            $form->multipleImage('activity_image')
                ->maxSize(2*1024*1024)
                ->move($move)
                ->uniqueName()
                ->accept('jpg,png,gif,jpeg')->saving(function ($paths){
                return json_encode($paths);
                })->autoUpload();

            $move1 = 'video/' . date('Ymd', time());
            $form->file('mp4')
                ->maxSize(2*1024*1024)
                ->uniqueName()
                ->accept('mp4')
                ->help('视频只支持mp4格式')
                ->move($move1)->autoUpload();

            $form->editor('activity_text')->languageUrl(url('tiny/langs/zh.js'));
            $form->time('activity_time')->rules('required', [
                'required' => '营业时间',
            ]);
            
            $form->hasMany('buttons', '按钮配置', function (Form\NestedForm $form) {
                $form->text('one_line', '第几行展示按钮？')->rules('required|regex:/^\d+$/', [
                    'required' => '行数必须填写',
                    'regex' => '行数必须为数字'
                ]);
                $form->text('button_text', '按钮名称');
                $form->text('button_link', '按钮链接');
                $form->text('button_inline', '内连按钮Callback');
            })->useTable();

            $form->radio('status')->options([0 => '关闭', 1 => '开启'])->default(1)->rules('required');
            $form->radio('type')->options([0 => '照片', 1 => '视频'])->default(0)->rules('required');
            $form->radio('is_pin','pinned')->options([0 => '关闭', 1 => '开启'])->default(0)->rules('required');
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}

