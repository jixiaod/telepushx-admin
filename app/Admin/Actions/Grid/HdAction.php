<?php

namespace App\Admin\Actions\Grid;

use Dcat\Admin\Actions\Response;
use Dcat\Admin\Grid\RowAction;
use Dcat\Admin\Traits\HasPermissions;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Admin\Forms\Huodong;
use Dcat\Admin\Widgets\Modal;

class HdAction extends RowAction
{
    /**
     * @return string
     */
	protected $title = '预览';

    /**
     * Handle the action request.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function render()
    {
        // 实例化表单类并传递自定义参数
        $form = Huodong::make()->payload(['id' => $this->getKey()]);;

        return Modal::make()
            ->lg()
            ->title($this->title)
            ->body($form)
            ->button(' 
            <a href="#" class="">
                <i class="feather grid-action-icon"></i> 预览
            </a>');
    }

}
