<?php

# Collectd turbostat plugin

require_once 'conf/common.inc.php';
require_once 'type/Default.class.php';
require_once 'type/GenericStacked.class.php';
require_once 'inc/collectd.inc.php';

$obj = new Type_Default($CONFIG);
switch($obj->args['type']) {
	case 'frequency':
		if ($CONFIG['version'] < 5) {
			$obj->data_sources = array('frequency');
		} else {
			$obj->data_sources = array('value');
		}
		$obj->ds_names = array('output' => 'Output');
		$obj->rrd_title = 'Frequency';
		$obj->rrd_vertical = 'GHz';
		$obj->rrd_format = '%5.1lf%s';
	break;
	case 'percent':
		$obj = new Type_GenericStacked($CONFIG);
		if ($CONFIG['version'] < 5) {
			$obj->data_sources = array('percent');
		} else {
			$obj->data_sources = array('value');
		}
		$obj->ds_names = 'State';
		$obj->rrd_title = sprintf('C states (%s)', $obj->args['pinstance']);
		$obj->rrd_vertical = '%';
		$obj->rrd_format = '%5.1lf';
	break;
	case 'power':
		$obj->data_sources = array('value');
		$obj->ds_names = array('ups' => 'UPS');
		$obj->rrd_title = sprintf('Power consumption (%s)', $obj->args['pinstance']);
		$obj->rrd_vertical = 'W';
		$obj->rrd_format = '%5.1lf%s';
	break;
	case 'temperature':
		$obj->data_sources = array('value');
		$obj->rrd_title = 'Temperature';
		$obj->rrd_vertical = '°C';
		$obj->rrd_format = '%5.1lf%s';
	break;
	case 'current':
		return;
#		$obj->data_sources = array('value');
#		$obj->rrd_title = 'SMI';
#		$obj->rrd_vertical = 'Nuumber';
#		$obj->rrd_format = '%5.0lf';
	break;
}

collectd_flush($obj->identifiers);
$obj->rrd_graph();
