<?php

namespace App\Admin\Forms;

use Dcat\Admin\Widgets\Form;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Contracts\LazyRenderable;
use App\Models\User;

class Huodong  extends Form implements LazyRenderable
{

    use LazyWidget; 
    /**
     * Handle the form request.
     *
     * @param array $input
     *
     * @return mixed
     */
    public function handle(array $input)
    {
        $id = $this->payload['id'] ?? null;
        $user = User::select('*')->where('tete_id', $input['tete_id'])->first();
        if(empty($user)) {

            return $this->response()->success('未找到用户ChatId')->refresh();
        }

        $endpoint = env('PUSH_API_URL','null') . "/api/preview/{$id}/{$user->id}";
        $client = new \GuzzleHttp\Client(['headers' => ["Content-type" => 'application/json']]);

        $response = $client->request('POST', $endpoint, ['body' => json_encode(['id' => $id])]);

        $statusCode = $response->getStatusCode();
        if ($statusCode == 200) {
            return $this->response()->success('预览成功')->refresh();
        } else {
            return $this->response()->success('预览失败')->refresh();
        }
    } 

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->text('tete_id','飞机ID')->type('number')->required();

    }

    /**
     * The data of the form.
     *
     * @return array
     */
    public function default()
    {
        return [

        ];
    }
}
