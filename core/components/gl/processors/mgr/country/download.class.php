<?php

/**
 * Update Clients an glCountry
 */
class modglCountrysDownloadProcessor extends modProcessor
{
    public $classKey = 'glCountry';

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
    public function process()
    {
        $content = $this->gl->getFileContent($this->classKey);
        if ($content == false) {
            return $this->failure($this->gl->lexicon('err_file_ns'));
        }

        $this->clearTable();
        $this->gl->createDefault();

        $lines = explode("\n", $content);
        array_pop($lines);

        $sql = "INSERT INTO {$this->modx->getTableName($this->classKey)} "
            . "(`id`, `iso`, `continent`, `name_ru`, `name_en`, `lat`, `lon`, `timezone`) VALUES "
            . "(:id, :iso, :continent, :name_ru, :name_en, :lat, :lon, :timezone)";
        $stmt = $this->modx->prepare($sql);

        foreach ($lines as $line) {
            $fields = explode("\t", $line);
            if ($stmt instanceof PDOStatement) {
                $stmt->bindValue(':id', $fields[0]);
                $stmt->bindValue(':iso', $fields[1]);
                $stmt->bindValue(':continent', $fields[2]);
                $stmt->bindValue(':name_ru', $fields[3]);
                $stmt->bindValue(':name_en', $fields[4]);
                $stmt->bindValue(':lat', $fields[5]);
                $stmt->bindValue(':lon', $fields[6]);
                $stmt->bindValue(':timezone', $fields[7]);
                if ($stmt->execute()) {
                } else {
                    throw new Exception ('Error add - ' . $this->modx->lastInsertId());
                }
            }
        }

        return $this->success();
    }

}

return 'modglCountrysDownloadProcessor';