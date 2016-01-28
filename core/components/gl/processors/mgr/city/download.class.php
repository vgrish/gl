<?php

/**
 * Update Clients an glCity
 */
class modglCitysDownloadProcessor extends modProcessor
{
	public $classKey = 'glCity';

	/** @var gl $gl */
	public $gl;

	/** {@inheritDoc} */
	public function initialize()
	{
		/** @var gl $gl */
		$this->gl = $this->modx->getService('gl');
		$this->gl->initialize($this->getProperty('context', $this->modx->context->key));

		return parent::initialize();
	}

	/** {@inheritDoc} */
	protected function clearTable()
	{
		$this->modx->query("DELETE FROM {$this->modx->getTableName($this->classKey)}");
		$this->modx->query("ALTER TABLE {$this->modx->getTableName($this->classKey)} AUTO_INCREMENT=1");
	}

	/** {@inheritDoc} */
	protected function getParents($parentClass = 'glRegion')
	{
		$sql = "SELECT id "
			. "FROM {$this->modx->getTableName($parentClass)} "
			. "WHERE active=1";
		$stmt = $this->modx->query($sql);

		return ($stmt) ? $stmt->fetchAll(PDO::FETCH_COLUMN) : array();
	}

	/** {@inheritDoc} */
	public function process()
	{
		$parents = $this->getParents();
		if (empty($parents)) {
			return $this->failure($this->gl->lexicon('err_parents_nfs'));
		}

		$content = $this->gl->getFileContent($this->classKey);
		if ($content == false) {
			return $this->failure($this->gl->lexicon('err_file_ns'));
		}

		$this->clearTable();
		$this->gl->createDefault();

		$lines = explode("\n", $content);
		array_pop($lines);

		$sql = "INSERT INTO {$this->modx->getTableName($this->classKey)} "
			. "(`id`, `region_id`, `name_ru`, `name_en`, `lat`, `lon`, `okato`) VALUES "
			. "(:id, :region_id, :name_ru, :name_en, :lat, :lon, :okato)";
		$stmt = $this->modx->prepare($sql);

		foreach ($lines as $line) {
			$fields = explode("\t", $line);
			if (!in_array($fields[1], $parents)) {
				continue;
			}
			if ($stmt instanceof PDOStatement) {
				$stmt->bindValue(':id', $fields[0]);
				$stmt->bindValue(':region_id', $fields[1]);
				$stmt->bindValue(':name_ru', $fields[2]);
				$stmt->bindValue(':name_en', $fields[3]);
				$stmt->bindValue(':lat', $fields[4]);
				$stmt->bindValue(':lon', $fields[5]);
				$stmt->bindValue(':okato', $fields[6]);
				if ($stmt->execute()) {
				} else throw new Exception ('Error add - ' . $this->modx->lastInsertId());
			}
		}

		return $this->success();
	}

}

return 'modglCitysDownloadProcessor';