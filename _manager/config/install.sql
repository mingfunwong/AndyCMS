CREATE TABLE `{DBPREFIX}system` (
  `id` int(10) UNSIGNED NOT NULL,
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `manager_name` varchar(255) DEFAULT '',
  `name` varchar(255) DEFAULT '',
  `attachment_dir` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `{DBPREFIX}system` (`id`, `create_time`, `update_time`, `manager_name`, `name`, `attachment_dir`) VALUES
(1, 1514732400, 1514732400, '网站管理后台', 'AndyCMS', 'uploads');

CREATE TABLE `{DBPREFIX}_admins` (
  `uid` int(10) UNSIGNED NOT NULL,
  `username` varchar(16) NOT NULL,
  `password` varchar(64) NOT NULL,
  `salt` varchar(10) NOT NULL,
  `memo` varchar(100) NOT NULL,
  `role` smallint(5) UNSIGNED DEFAULT NULL,
  `status` tinyint(1) UNSIGNED DEFAULT 1 COMMENT '1=正常，2=冻结'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}_models` (
  `id` smallint(10) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(50) NOT NULL,
  `import` tinyint(4) DEFAULT NULL,
  `export` tinyint(4) DEFAULT NULL,
  `single` tinyint(4) DEFAULT NULL,
  `level` tinyint(4) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `order` int(5) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `{DBPREFIX}_models` (`id`, `name`, `description`, `import`, `export`, `single`, `level`, `icon`, `order`) VALUES
(1, 'system', '系统设置', NULL, NULL, 1, NULL, 'fa-cog', 0);


CREATE TABLE `{DBPREFIX}_model_fields` (
  `id` mediumint(10) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(40) NOT NULL,
  `model` smallint(10) UNSIGNED NOT NULL DEFAULT 0,
  `type` varchar(20) DEFAULT NULL,
  `values` tinytext DEFAULT NULL,
  `rules` tinytext DEFAULT NULL,
  `ruledescription` tinytext DEFAULT NULL,
  `searchable` tinyint(1) UNSIGNED DEFAULT NULL,
  `listable` tinyint(1) UNSIGNED DEFAULT NULL,
  `order` int(5) UNSIGNED DEFAULT NULL,
  `editable` tinyint(1) UNSIGNED DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `{DBPREFIX}_model_fields` (`id`, `name`, `description`, `model`, `type`, `values`, `rules`, `ruledescription`, `searchable`, `listable`, `order`, `editable`) VALUES
(1, 'name', '站点名称', 1, 'input', '', 'required', '', 1, 1, 10, 1),
(2, 'manager_name', '后台网页标题', 1, 'input', '', 'required', '', 1, 1, 20, 1),
(3, 'attachment_dir', '文件上传路径', 1, 'input', '', '', '', 1, 1, 30, NULL);

CREATE TABLE `{DBPREFIX}_roles` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `rights` varchar(255) NOT NULL,
  `models` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `{DBPREFIX}_roles` (`id`, `name`, `rights`, `models`) VALUES
(1, '超级管理员', '', '');

CREATE TABLE `{DBPREFIX}_sessions` (
  `id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `data` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}_throttles` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `{DBPREFIX}system`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `{DBPREFIX}_admins`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `group` (`role`);

ALTER TABLE `{DBPREFIX}_models`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

ALTER TABLE `{DBPREFIX}_model_fields`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`,`model`),
  ADD KEY `model` (`model`);

ALTER TABLE `{DBPREFIX}_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

ALTER TABLE `{DBPREFIX}_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `last_activity_idx` (`last_activity`);

ALTER TABLE `{DBPREFIX}_throttles`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `{DBPREFIX}system`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `{DBPREFIX}_admins`
  MODIFY `uid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `{DBPREFIX}_models`
  MODIFY `id` smallint(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `{DBPREFIX}_model_fields`
  MODIFY `id` mediumint(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `{DBPREFIX}_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `{DBPREFIX}_throttles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;