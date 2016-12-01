<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Admin\User;
use App\Model\Admin\GeTui;

use Illuminate\Http\Request;

class GetuiController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		return view('Admin.Getui.index');

	}

	public function send()
	{
		$app = \Request::input('APP');			//APP 应用名称编号
		$type = \Request::input('type');		//通知类型
		$phone = \Request::input('phone');		//电话号码
		$title = \Request::input('title');		//标题内容（此位置在下面的程序中由 type 控制）
		$message = \Request::input('message');	//正文内容
		$url = \Request::input('url');			//需要跳转的 url
		$clientId = \Request::input('clientid');//用户应用的 client id

		if($type == 1)
			$title = '您有一个新的系统通知';
		if($type == 2)
			$title = '您有一个新的活动通知';
		if($type == 4)
			$title = '您有一个新的优惠券通知';
		if($type == 5)
			$title = '您有一个新的保养通知';
		else
		{
			$msgId = 1;
		}

		$appMessage = json_encode(array(
			'msgType'=>$type
			,'msgId'=>$msgId
			,'msgText'=>$message
			,'locKey'=>$title
			// ,'url'=>
			// ,'imageUrl'=>
		));

		$geTui = new GeTui();
		$titleLocKey = '{标题名称}';
		$locKey = $title;

		$actionLockey='';

		$result = $geTui->IGtTransmissionTemplateDemo($app,$appMessage,$clientId,$titleLocKey,$locKey,$actionLockey);

		if($result)
			return redirect('getui/index')->with('message','推送成功');
		return redirect('getui/index')->with('message','推送失败');

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
