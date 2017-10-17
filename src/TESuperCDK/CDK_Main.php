<?php

/*                             Copyright (c) 2017-2018 TeaTech All right Reserved.
 *
 *      ████████████  ██████████           ██         ████████  ██           ██████████    ██          ██
 *           ██       ██                 ██  ██       ██        ██          ██        ██   ████        ██
 *           ██       ██                ██    ██      ██        ██          ██        ██   ██  ██      ██
 *           ██       ██████████       ██      ██     ██        ██          ██        ██   ██    ██    ██
 *           ██       ██              ████████████    ██        ██          ██        ██   ██      ██  ██
 *           ██       ██             ██          ██   ██        ██          ██        ██   ██        ████
 *           ██       ██████████    ██            ██  ████████  ██████████   ██████████    ██          ██
**/

# Plugin Information
/**
	* Author: Teaclon(锤子)
	* Open source license: GNU General Public License v3.0
	* Open source storage location: Github and Coding
	* Web from Github: 
	* Web from Coding: 
	* Contacct: ["QQ", 3385815158]
	* Donate Author: https://pl.zxda.net/plugins/877.html
	* Last-Update: 2017-10-17, 10:00
	* Version: 1.0.0
**/

# Plugin Copyright & Statement
/**
	* Copyright Teaclon © 2017 All right Reserved.
	* This plugin code complies with the GPL code open source license open source, please abide by this license to use this code.
	* Reprinted or used for commercial purposes, please be sure to inform the author, and indicate the original author of the code.
	* If you comply with the above statement, you will be eligible for this code.
**/

# 插件信息
/**
	* 作者: Teaclon(锤子)
	* 代码开源许可证: GNU General Public License v3.0
	* 代码开源存放地点: Github and Coding
	* Github项目地址: 
	* Coding项目地址: 
	* 捐赠作者: https://pl.zxda.net/plugins/877.html
	* 与作者获取联系: ["QQ", 3385815158]
	* 最后更新时间: 2017-10-17, 22:00
	* 版本: 1.0.0
**/

# 插件使用说明 & 版权声明
/**
	* Copyright Teaclon © 2017 All right Reserved.
	* 本插件代码遵守GPL代码开源许可证开源, 请各位遵守本许可证使用本代码.
	* 转载或者作为商业用途, 请务必通知本作者, 并且注明代码原作者.
	* 如果你遵守了以上声明, 你将获得本代码的使用资格.
**/

namespace TESuperCDK;

use pocketmine\utils\TextFormat;             #调用TextFormat,颜色;
use pocketmine\command\CommandSender;        #发送指令;
use pocketmine\plugin\PluginBase;            #插件基础;
use pocketmine\command\Command;              #指令;
use pocketmine\event\Listener;               #监听;
use pocketmine\utils\Config;                 #配置文件;
use pocketmine\Server;                       #服务器;
use pocketmine\Player;                       #玩家;

// class 类名 ;
class CDK_Main extends PluginBase implements Listener
{
	const PREFIX = "[TESuperCDK] ";
	private $config;
	public function onLoad() //插件初始化时, 必须要初始本插件的东西(本函数按照需要存在).
	{
		// 这里写你需要写的代码;
		$this->getLogger()->info("初始化本插件......");
		if(is_dir($this->getDataFolder())) // 判断是否存在本插件的专属文件夹;
		{
			mkdir($this->getDataFolder());
		}
		// -----END-----
	}
	
	public function onEnable() // 插件正式加载(本函数必须存在);
	{
		// 这里写你需要写的代码;
		$this->getLogger()->info("初始化完毕.");
		$this->config = new Config($this->getDataFolder()."config.yml", Config::YAML, []); // 生成配置文件;
		$this->getLogger()->info("插件已加载.");
		// -----END-----
	}
	
	public function onDisable() // 插件取消加载(本函数按照需要存在);
	{
		// 这里写你需要写的代码;
		$this->config->save();
		$this->getLogger()->info("插件已卸载.");
		// -----END-----
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) // 识别指令函数(变量参数可自命名);
	{
		// 本插件代码仅对于初学者使用. 为了方便理解, 代码区域将以if-else语句代替switch语句;
		if($cmd->getName() === "tscdkhelp") // 如果指令为"tscdkhelp";
		{
			if(!$sender->isOp())
			{
				$sender->sendMessage("§c".self::PREFIX."你没有权限使用该命令.");
				return true;
			}
			
			$sender->sendMessage(self::PREFIX."---HELPER---");
			$sender->sendMessage("/tscdkhelp 召唤帮助助手");
			$sender->sendMessage("/tscdk 生成(add) <卡密>");
			$sender->sendMessage("/tscdk 删除(del) <卡密>");
			$sender->sendMessage("/tscdk 类型(type) <卡密> <类型>");
			$sender->sendMessage("/tscdk 修改(modify) <卡密> <内容>");
			$sender->sendMessage("/tscdk 指定(specify) <卡密> <指定的使用者名称>");
			$sender->sendMessage("具体操作请访问https://pl.zxda.net/plugins/877.html");
			return true;
		}
		
		if($cmd->getName() === "tscdk")
		{
			if(!isset($args[0])) //如果没有输入指令
			{
				$sender->sendMessage("§c".self::PREFIX."未检测到指令输入, 请输入 /tscdkhelp 查看帮助.");
				return true;
			}
			
			if($args[0] === "生成" || $args[0] === "add")
			{
				if(!isset($args[1])) //如果没有输入指令
				{
					$sender->sendMessage("§c".self::PREFIX."未检测到自定义卡密输入, 将使用随机卡密生成空卡密.");
					$this->CreateCDK($sender);
					return true;
				}
				else
				{
					if(strlen($args[1]) >= 10) // 判断字符总和是否大于等于10;
					{
						$sender->sendMessage("§e".self::PREFIX."正在创建空卡密§a".$args[1]."§e.");
						$this->CreateCDK($sender);
					}
					else
					{
						$sender->sendMessage("§c".self::PREFIX."输入的卡密过短, 请重新输入.");
					}
				}
				return true;
			}
			elseif($args[0] === "删除" || $args[0] === "del")
			{
				return true;
			}
			elseif($args[0] === "类型" || $args[0] === "type")
			{
				return true;
			}
			elseif($args[0] === "修改" || $args[0] === "modify")
			{
				return true;
			}
			elseif($args[0] === "指定" || $args[0] === "specify")
			{
				return true;
			}
			else
			{
				$sender->sendMessage("§c".self::PREFIX."指令不存在, 请输入 /tscdkhelp 查看帮助.");
				return true;
			}
		}
	}
	
	
	protected function CreateCDK($sender, $cdk = null)
	{
		if(strlen($cdk)==null)
		{
			$cdk_01 = mt_rand(100000, 200000);
			$cdk_02 = mt_rand(300000, 400000);
			$cdk_03 = mt_rand(500000, 600000);
			$cdk_04 = mt_rand(700000, 800000);
			$cdk_05 = mt_rand(900000, 999999);
			$cdk = $cdk_01.$cdk_02.$cdk_03.$cdk_04.$cdk_05;
		}
		$this->config->setNested($cdk.".type", null);
		$this->config->setNested($cdk.".is-use", false);
		$this->config->setNested($cdk.".creater", $sender->getName());
		$this->config->setNested($cdk.".create-date", date("Y-m-d"));
		$this->config->setNested($cdk.".user", null);
		$this->config->setNested($cdk.".used-date", null);
		$this->config->save();
		
		$sender->sendMessage("§a".self::PREFIX."空卡密创建完毕, 卡密为: §f".$cdk."§a, 请按照以下步骤配置.");
		$sender->sendMessage("§e".self::PREFIX."输入指令 /tscdk 类型 ".$cdk." <类型> 配置卡密.");
		$sender->sendMessage(self::PREFIX."目前有以下3种: cmd, money, gamemode.");
		unset($cdk);
	}
	
}
?>
