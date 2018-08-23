CREATE TABLE `customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '' COMMENT '姓名',
  `phone` varchar(200) NOT NULL DEFAULT '' COMMENT '手机号',
  `phone_bak` varchar(200) NOT NULL DEFAULT '' COMMENT '备用手机号',
  `height` varchar(200) NOT NULL DEFAULT '' COMMENT '身高',
  `weight` varchar(200) NOT NULL DEFAULT '' COMMENT '体重',
  `feature` varchar(200) NOT NULL DEFAULT '' COMMENT '特征',
  `clothing_length` varchar(200) NOT NULL DEFAULT '' COMMENT '衣长',
  `shoulder_width` varchar(200) NOT NULL DEFAULT '' COMMENT '肩宽',
  `sleeve_lenght` varchar(200) NOT NULL DEFAULT '' COMMENT '袖长',
  `arm` varchar(200) NOT NULL DEFAULT '' COMMENT '手臂',
  `bust` varchar(200) NOT NULL DEFAULT '' COMMENT '胸围',
  `waistline` varchar(200) NOT NULL DEFAULT '' COMMENT '腰围',
  `swing_around` varchar(200) NOT NULL DEFAULT '' COMMENT '摆围',
  `upper_waist_section` varchar(200) NOT NULL DEFAULT '' COMMENT '上腰节',
  `pants_length` varchar(200) NOT NULL DEFAULT '' COMMENT '裤长',
  `pants_up_waist` varchar(200) NOT NULL DEFAULT '' COMMENT '上腰（裤子）',
  `pants_down_waist` varchar(200) NOT NULL DEFAULT '' COMMENT '下腰（裤子）',
  `pants_hip` varchar(200) NOT NULL DEFAULT '' COMMENT '臀围（裤子）',
  `straight` varchar(200) NOT NULL DEFAULT '' COMMENT '直裆',
  `thigh` varchar(200) NOT NULL DEFAULT '' COMMENT '大腿',
  `mid_range` varchar(200) NOT NULL DEFAULT '' COMMENT '中裆',
  `lower_leg` varchar(200) NOT NULL DEFAULT '' COMMENT '小腿',
  `trousers` varchar(200) NOT NULL DEFAULT '' COMMENT '裤口',
  `skirt_length` varchar(200) NOT NULL DEFAULT '' COMMENT '裙长',
  `skirt_up_waist` varchar(200) NOT NULL DEFAULT '' COMMENT '上腰（裙子）',
  `skirt_down_waist` varchar(200) NOT NULL DEFAULT '' COMMENT '下腰（裙子）',
  `skirt_hip` varchar(200) NOT NULL DEFAULT '' COMMENT '臀围（裙子）',
  `bak` varchar(200) NOT NULL DEFAULT '' COMMENT '备注',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0：删除, 1:未删除',
  `created_at` int(11) NOT NULL COMMENT '创建时间',
  `updated_at` int(11) NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='客户表';
