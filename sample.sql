-- 导出 rms 的数据库结构
CREATE DATABASE IF NOT EXISTS `rms`;
USE `rms`;

-- 导出  表 rms.cache_login 结构
CREATE TABLE IF NOT EXISTS `cache_login` (
  `CacheID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `RealName` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `SessionID` varchar(26) COLLATE utf8_unicode_ci NOT NULL,
  `ErrorTimes` int(1) DEFAULT NULL,
  `ExpTime` int(10) NOT NULL,
  `CacheTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `IP` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`CacheID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `menus` (
  `MenuID` int(11) NOT NULL AUTO_INCREMENT COMMENT '菜单ID',
  `FatherID` int(11) NOT NULL DEFAULT '0' COMMENT '父菜单ID',
  `Menuname` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '菜单名称',
  `MenuIcon` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '菜单图标（FontAwesome类）',
  `PageFile` varchar(20) COLLATE utf8_unicode_ci DEFAULT 'View' COMMENT '对应文件路径',
  `PageDOS` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '对应文件名',
  `isPublic` int(1) DEFAULT '0' COMMENT '是否公有页面',
  PRIMARY KEY (`MenuID`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='系统 - 菜单列表';

INSERT INTO `menus` (`MenuID`, `FatherID`, `Menuname`, `MenuIcon`, `PageFile`, `PageDOS`, `isPublic`) VALUES
	(1, 0, '系统', 'cogs', '', '', 0),
	(3, 1, '菜单管理', 'bars', 'Sys', 'ManageMenu.php', 0),
	(4, 1, '用户管理', 'user-circle', 'User', 'toList.php', 0),
	(16, 1, '清空缓存', 'trash', 'Sys', 'EmptyCache.php', 0),
	(17, 1, '发布全局公告', 'bullhorn', 'Sys', 'toPubGlobalNotice.php', 0),
	(18, 1, '操作记录', 'list-alt', 'Sys', 'toLogList.php', 0),
	(19, 1, '角色管理', 'users', 'Role', 'toList.php', 0),
	(27, 0, '报修', 'envelope', '', '', 0),
	(28, 27, '我的报修单', 'list-alt', 'Repair', 'UserOrders.php', 0),
	(29, 27, '新开报修单', 'plus-circle', 'Repair', 'CreateOrder.php', 0),
	(30, 0, '维修', 'wrench', '', '', 0),
	(31, 30, '维修单', 'list-alt', 'Repair', 'EngineerOrders.php', 0),
	(32, 30, '送修单', 'upload', 'SendRepair', 'toList.php', 0),
	(33, 0, '统计', 'bar-chart', '', '', 0),
	(34, 27, '班级报修单', 'users', 'Repair', 'ClassOrders.php', 0);

CREATE TABLE IF NOT EXISTS `repairs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_user_id` int(11) NOT NULL COMMENT '发起人的用户ID',
  `place` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '设备所在场室',
  `equipment` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '设备名称',
  `title` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL COMMENT '故障内容',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '报修单状态(0完毕1待接单2已接单3送修)',
  `engineer_id` int(11) DEFAULT NULL COMMENT '工程师的用户ID',
  `repair_content` text COLLATE utf8_unicode_ci COMMENT '维修回复内容',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '开单时间',
  `receive_time` varchar(19) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '接单时间',
  `repair_time` varchar(19) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '维修时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `roles` (
  `RoleID` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `RoleName` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色名称',
  `Brief` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '备注',
  `isSuper` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否系统角色(0非1是)(系统角色不可删除)',
  `isEngineer` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否工程师角色(0非1是)',
  `isClassTch` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`RoleID`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='角色 - 角色列表';

INSERT INTO `roles` (`RoleID`, `RoleName`, `Brief`, `isSuper`, `isEngineer`, `isClassTch`) VALUES
	(1, '超级管理员', '最高权限用户，内置角色不可删除', '1', '1', 0),
	(6, '工程师', '拥有 报修及送修处理 权限', '0', '1', 0),
	(7, '班主任', '拥有 报修、查看其管理的班级维修单 权限', '0', '0', 1),
	(8, '电教员', '拥有 普通报修 权限', '0', '0', 0),
	(9, '管理员', '拥有 除系统敏感操作外 所有权限', '0', '1', 0);

CREATE TABLE IF NOT EXISTS `role_purview` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `RoleID` int(11) NOT NULL COMMENT '角色ID（与role_list关联）',
  `PurvID` int(11) NOT NULL COMMENT '权限ID（与sys_menu关联）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=152 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='角色 - 角色与权限关联';

INSERT INTO `role_purview` (`id`, `RoleID`, `PurvID`) VALUES
	(151, 1, 34),
	(150, 1, 33),
	(149, 1, 32),
	(148, 1, 31),
	(147, 1, 30),
	(146, 1, 29),
	(145, 1, 28),
	(144, 1, 27),
	(143, 1, 19),
	(142, 1, 18),
	(141, 1, 17),
	(140, 1, 16),
	(139, 1, 4),
	(83, 6, 30),
	(84, 6, 31),
	(85, 6, 32),
	(138, 1, 3),
	(123, 7, 29),
	(122, 7, 28),
	(121, 7, 27),
	(103, 8, 27),
	(104, 8, 28),
	(105, 8, 29),
	(137, 1, 1),
	(124, 7, 34),
	(125, 9, 1),
	(126, 9, 4),
	(127, 9, 17),
	(128, 9, 18),
	(129, 9, 19),
	(130, 9, 27),
	(131, 9, 28),
	(132, 9, 29),
	(133, 9, 34),
	(134, 9, 30),
	(135, 9, 31),
	(136, 9, 32);

CREATE TABLE IF NOT EXISTS `send_repairs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `repair_id` int(11) NOT NULL,
  `factory_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `send_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `est_return_time` varchar(19) COLLATE utf8_unicode_ci NOT NULL,
  `return_time` varchar(19) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `create_user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `sys_log` (
  `LogID` int(11) NOT NULL AUTO_INCREMENT,
  `LogType` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `LogContent` text COLLATE utf8_unicode_ci NOT NULL,
  `LogUser` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `LogIP` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `LogTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`LogID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `users` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `UserName` varchar(16) COLLATE utf8_unicode_ci NOT NULL COMMENT '登录用户名',
  `RealName` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户真实姓名',
  `Password` text COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
  `salt` varchar(8) COLLATE utf8_unicode_ci NOT NULL COMMENT '加密字符串',
  `RoleID` int(11) NOT NULL COMMENT '角色ID（与role_list关联）',
  `Status` int(1) NOT NULL COMMENT '状态',
  `originPassword` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '初始8位密码',
  `SchoolGrade` int(2) NOT NULL,
  `SchoolClass` int(2) NOT NULL,
  `RegiDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '注册时间',
  `LastDate` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '最后登录时间',
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `Index 2` (`UserName`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='用户 - 用户资料库';

INSERT INTO `users` (`UserID`, `UserName`, `RealName`, `Password`, `salt`, `RoleID`, `Status`, `originPassword`, `SchoolGrade`, `SchoolClass`, `RegiDate`, `LastDate`) VALUES
	(1, 'super', '超级管理员', 'MDQxMjY4YWYxNzZiNjE3YTBmOGM3Yzk2MWIwZGZiNzI2MDQxMjY4YWEwZjhjN2M5NjFiMTIzNDVmMTc2YjYxNzBkZmI3Mg==', 'uxQBpbCB', 1, 2, '', 0, 0, '2017-09-06 06:36:11', '2017-12-15 22:30:46'),
	(2, 'testdjy', '测试电教员', 'NGUxMTBhY2ZjZjJmMDI5M2JhYmUzN2Q1MDMwYzlkMWY2NGUxMTBhYzNiYWJlMzdkNTAzMTIzNDVmY2YyZjAyOTBjOWQxZg==', 'VLRWnnvn', 8, 2, '', 7, 1, '2017-12-15 22:31:24', '2017-12-15 22:32:29'),
	(3, 'testbzr', '测试班主任', 'NjlkNDVmZGJlMWE4YWViMDEwYjkxYzQxODdlNzczZTU2NjlkNDVmZDAxMGI5MWM0MTg3MTIzNDViZTFhOGFlYmU3NzNlNQ==', 'vVbrZnvZ', 7, 2, '', 7, 1, '2017-12-15 22:31:47', '2017-12-15 22:32:54'),
	(4, 'testgcs', '测试工程师', 'YjVjYjhlOGZlNGE0NTkwZTc0YzM4ZjVhMzY1NzkyNmE2YjVjYjhlOGU3NGMzOGY1YTM2MTIzNDVmZTRhNDU5MDU3OTI2YQ==', 'UCqarERJ', 6, 2, '', -1, -1, '2017-12-15 22:32:03', '2017-12-15 22:33:16');
