<?php

namespace App\Http\Controllers\Api\WebApi;

use App\Models\SsNode;
use App\Models\User;
use Illuminate\Http\Request;

class VNetController extends BaseController {
	// 获取节点信息
	public function getNodeInfo($id) {
		$node = SsNode::query()->whereId($id)->first();

		return $this->returnData('获取节点信息成功', 'success', 200, [
			'id'           => $node->id,
			'method'       => $node->method,
			'protocol'     => $node->protocol,
			'obfs'         => $node->obfs,
			'obfs_param'   => $node->obfs_param,
			'is_udp'       => $node->is_udp,
			'speed_limit'  => $node->speed_limit,
			'client_limit' => $node->client_limit,
			'single'       => $node->single,
			'port'         => $node->port,
			'passwd'       => $node->passwd,
			'push_port'    => $node->push_port,
		]);
	}

	// 获取节点可用的用户列表
	public function getUserList(Request $request, $id) {
		$node = SsNode::query()->whereId($id)->first();
		$users = User::query()->where('status', '<>', -1)->whereEnable(1)->where('level', '>=', $node->level)->get();
		$data = [];

		foreach($users as $user){
			$new = [
				'uid'         => $user->id,
				'port'        => $user->port,
				'passwd'      => $user->passwd,
				'method'      => $user->method,
				'protocol'    => $user->protocol,
				'obfs'        => $user->obfs,
				'obfs_param'  => $node->obfs_param,
				'speed_limit' => $user->speed_limit,
				'enable'      => $user->enable
			];
			array_push($data, $new);
		}

		if($data){
			return $this->returnData('获取用户列表成功', 'success', 200, $data, ['updateTime' => time()]);
		}

		return $this->returnData('获取用户列表失败');
	}
}
