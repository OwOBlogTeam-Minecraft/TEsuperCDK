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

use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\command\ConsoleCommandSender;

// class 类名 ;
class CDK_Main extends PluginBase implements Listener
{
	const PREFIX = "[TESuperCDK] ";
	private $config;
	
	public function onLoad() //插件初始化时, 必须要初始本插件的东西(本函数按照需要存在).
	{
		// 这里写你需要写的代码;
		$this->getLogger()->info("初始化本插件......");
		if(!is_dir($this->getDataFolder())) // 判断是否存在本插件的专属文件夹;
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
				$sender->sendMessage(self::PREFIX."---HELPER---");
				$sender->sendMessage("/tscdkhelp 召唤帮助助手");
				$sender->sendMessage("/tscdk 使用(use) <卡密>");
				return true;
			}
			
			$sender->sendMessage(self::PREFIX."---HELPER---");
			$sender->sendMessage("/tscdkhelp 召唤帮助助手");
			$sender->sendMessage("/tscdk 生成(add) <卡密>");
			$sender->sendMessage("/tscdk 删除(del) <卡密>");
			$sender->sendMessage("/tscdk 使用(use) <卡密>");
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
				if(!$sender->isOp())
				{
					$sender->sendMessage("§c".self::PREFIX."你没有权限使用该命令.");
					return true;
				}
				
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
						$this->CreateCDK($sender, $args[1]);
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
				if(!$sender->isOp())
				{
					$sender->sendMessage("§c".self::PREFIX."你没有权限使用该命令.");
					return true;
				}
				
				if(!isset($args[1])) //如果没有输入指令
				{
					$sender->sendMessage("§c".self::PREFIX."请输入卡密.");
					$this->CreateCDK($sender);
					return true;
				}
				
				if(!isset($this->config->getAll()[$args[1]]))
				{
					$sender->sendMessage("§c".self::PREFIX."卡密不存在.");
				}
				else
				{
					$this->config->remove($args[1]);
					$this->config->save();
					$sender->sendMessage("§a".self::PREFIX."卡密删除成功.");
				}
				return true;
			}
			elseif($args[0] === "修改" || $args[0] === "modify")
			{
				if(!$sender->isOp())
				{
					$sender->sendMessage("§c".self::PREFIX."你没有权限使用该命令.");
					return true;
				}
				
				if(!isset($args[1])) //如果没有输入指令
				{
					$sender->sendMessage("§c".self::PREFIX."请输入卡密.");
					$this->CreateCDK($sender);
					return true;
				}
				
				if(!isset($this->config->getAll()[$args[1]]))
				{
					$sender->sendMessage("§c".self::PREFIX."卡密不存在.");
					return true;
				}
				
				if($this->config->getNested($args[1].".command") != null)
				{
					$sender->sendMessage("§c".self::PREFIX."卡密已被定义.");
					return true;
				}
				
				if(!isset($args[2]))
				{
					$sender->sendMessage("§c".self::PREFIX."请输入内容.");
					return true;
				}
				
				$this->config->setNested($args[1].".command", str_replace("@", " ", $args[2]));
				$this->config->save();
				$sender->sendMessage("§a".self::PREFIX."卡密类型更改成功.");
				return true;
			}
			elseif($args[0] === "指定" || $args[0] === "specify")
			{
				if(!$sender->isOp())
				{
					$sender->sendMessage("§c".self::PREFIX."你没有权限使用该命令.");
					return true;
				}
				
				if(!isset($args[1])) //如果没有输入指令
				{
					$sender->sendMessage("§c".self::PREFIX."请输入卡密.");
					$this->CreateCDK($sender);
					return true;
				}
				
				if(!isset($this->config->getAll()[$args[1]]))
				{
					$sender->sendMessage("§c".self::PREFIX."卡密不存在.");
					return true;
				}
				
				if(isset($this->config->getAll()[$args[1]]["specified-person"]))
				{
					$sender->sendMessage("§c".self::PREFIX."该卡密已存在所有者.");
					return true;
				}
				
				$this->config->setNested($args[1].".specified-person", $args[2]);
				$this->config->save();
				$sender->sendMessage("§a".self::PREFIX."卡密更改成功.");
				return true;
			}
			elseif($args[0] === "使用" || $args[0] === "use")
			{
				if(!$sender instanceof Player)
				{
					$sender->sendMessage("§c".self::PREFIX."请在游戏内使用这个指令.");
					return true;
				}
				
				if(!isset($args[1])) //如果没有输入指令
				{
					$sender->sendMessage("§c".self::PREFIX."请输入卡密.");
					$this->CreateCDK($sender);
					return true;
				}
				
				if(!isset($this->config->getAll()[$args[1]]))
				{
					$sender->sendMessage("§c".self::PREFIX."卡密不存在.");
					return true;
				}
				
				if(isset($this->config->getAll()[$args[1]]["specified-person"]))
				{
					if($sender->getName() !== $this->config->getAll()[$args[1]]["specified-person"])
					{
						$sender->sendMessage("§c".self::PREFIX."你没有权限使用这个卡密.");
						return true;
					}
					else
					{
						$this->UseCDK($sender, $args[1]);
					}
				}
				else
				{
					$this->UseCDK($sender, $args[1]);
				}
				return true;
			}
			else
			{
				$sender->sendMessage("§c".self::PREFIX."指令不存在, 请输入 /tscdkhelp 查看帮助.");
				return true;
			}
		}
	}
	
	
	protected function UseCDK($sender, $cdk = null) // 用来使用卡密的函数;
	{
		if($this->config->getAll()[$cdk]["command"] == null)
		{
			$sender->sendMessage("§c".self::PREFIX."卡密为空, 请联系管理员设置.");
			return true;
		}
		
		if($this->config->getAll()[$cdk]["is-use"] != false)
		{
			$this->config->setNested($cdk.".is-use", true);
			$this->config->setNested($cdk.".user", $sender->getName());
			$this->config->setNested($cdk.".used-date", date("Y-m-d"));
			$this->config->save();
			
			$command = str_replace("{user}", $sender->getName(), $this->config->getAll()[$cdk]["command"]);
			$this->getServer()->dispatchCommand(new ConsoleCommandSender(), $command);
			$sender->sendMessage("§a".self::PREFIX."使用成功, 卡密已作废.");
		}
		else
		{
			$this->config->setNested($cdk.".description", $this->config->getAll()[$cdk]["command"]);
			$this->config->setNested($cdk.".command", "say ".$sender->getName()."尝试非法使用作废的卡密!");
			$this->config->save();
			
			$sender->sendMessage("§c".self::PREFIX."本次的非法使用已记录在使用日志中.");
			
		}
	}
	
	
	protected function CreateCDK($sender, $cdk = null) // 用来创建卡密的函数;
	{
		if(strlen($cdk) == null)
		{
			$cdk_01 = mt_rand(100000, 200000);
			$cdk_02 = mt_rand(300000, 400000);
			$cdk_03 = mt_rand(500000, 600000);
			$cdk = $cdk_01.$cdk_02.$cdk_03;
		}
		$this->config->setNested($cdk.".command", null);
		$this->config->setNested($cdk.".is-use", false);
		$this->config->setNested($cdk.".creater", $sender->getName());
		$this->config->setNested($cdk.".create-date", date("Y-m-d"));
		$this->config->setNested($cdk.".user", null);
		$this->config->setNested($cdk.".used-date", null);
		$this->config->save();
		
		$sender->sendMessage("§a".self::PREFIX."空卡密创建完毕, 卡密为: §f".$cdk."§a, 请按照以下步骤配置.");
		$sender->sendMessage("§e".self::PREFIX."输入指令 /tscdk 修改 ".$cdk." <内容> 配置卡密.");
		unset($cdk);
	}
	
}
?>
