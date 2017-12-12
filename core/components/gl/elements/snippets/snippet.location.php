<?php
/** @var array $scriptProperties */
$context = $scriptProperties['context'] = $modx->getOption('context', $scriptProperties, $modx->context->key, true);
$class = $scriptProperties['class'] = $modx->getOption('class', $scriptProperties, 'glCity', true);
$where = $scriptProperties['where'] = $modx->getOption('where', $scriptProperties, '{}', true);
$limit = $scriptProperties['limit'] = $modx->getOption('limit', $scriptProperties, 10, true);
$offset = $scriptProperties['offset'] = $modx->getOption('offset', $scriptProperties, 0, true);
$sortby = $scriptProperties['sortby'] = $modx->getOption('sortby', $scriptProperties, 'name_ru', true);
$sortdir = $scriptProperties['sortdir'] = $modx->getOption('sortdir', $scriptProperties, 'ASC', true);
$tpl = $scriptProperties['tpl'] = $modx->getOption('tpl', $scriptProperties, 'tpl.gl.location', true);
$idx = $scriptProperties['idx'] = $modx->getOption('idx', $scriptProperties, 0, true);
$outputSeparator = $scriptProperties['outputSeparator'] = $modx->getOption('outputSeparator', $scriptProperties, "\n",
    true);
/** @var gl $gl */
if (!$gl = $modx->getService('gl', 'gl',
    $modx->getOption('gl_core_path', null, $modx->getOption('core_path') . 'components/gl/') . 'model/gl/',
    $scriptProperties)
) {
    return 'Could not load gl class!';
}

$gl->initialize($context, $scriptProperties);

$rows = array();
$where = array(
    'active'  => 1,
);

if (!empty($scriptProperties['where'])) {
    if (is_array($scriptProperties['where'])) {
        $tmp = $scriptProperties['where'];
    } else {
        $tmp = $modx->fromJSON($scriptProperties['where']);
    }
    if (is_array($tmp) AND !empty($tmp)) {
        $where = array_merge($where, $tmp);
    }
}

$q = $modx->newQuery($class);
$q->where($where);
$q->limit($limit, $offset);
$q->sortby($sortby, $sortdir);
$q->select($modx->getSelectColumns($class, $class));
if ($q->prepare() AND $q->stmt->execute()) {
    $rows = $q->stmt->fetchAll(PDO::FETCH_ASSOC);
}

$output = array();
$idx = $offset + $idx;

foreach ($rows as $row) {
    $row['idx'] = $idx++;
    $row['class'] = $class;
    $output[] = $gl->getChunk($tpl, $row);
}

$output = implode($outputSeparator, $output);

if (!empty($toPlaceholder)) {
    $modx->setPlaceholder($toPlaceholder, $output);
} else {
    return $output;
}
