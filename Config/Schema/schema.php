<?php
App::uses('ClassRegistry', 'Utility');
App::uses('JobScheduler.JobScheduler', 'Model');

class AppSchema extends CakeSchema {

  public $connection = 'default';

  public function before($event = array())
  {
    // No Database cache clear will be needed afterwards
    $db = ConnectionManager::getDataSource($this->connection);
    $db->cacheSources = false;

    if (isset($event['drop'])) {
      switch ($event['drop']) {
        case 'job_schedulers':
          $JobScheduler = ClassRegistry::init('JobScheduler.JobScheduler');
          $JobScheduler->useDbConfig = $this->connection;
          $backup_file = __DIR__ . '/job_schedulers_' . date('y_m_d') . '.csv';
          if(!file_exists($backup_file)) {
            touch($backup_file);
            chmod($backup_file, 0766);
          }
          try {
            $JobScheduler->query("COPY cm_job_schedulers TO '" . $backup_file . "' DELIMITER ',' CSV HEADER");
          } catch (Exception $e){
            // Ignore the Exception
          }
          break;
      }
    }

    return true;
  }

  public function after($event = array())
  {
    if (isset($event['create'])) {
      switch ($event['create']) {
        case 'job_schedulers':
          $JobScheduler = ClassRegistry::init('JobScheduler.JobScheduler');
          $JobScheduler->useDbConfig = $this->connection;
          // Add the constraints or any other initializations
          $JobScheduler->query("ALTER TABLE ONLY public.cm_job_schedulers ADD CONSTRAINT cm_job_schedulers_co_id_fkey FOREIGN KEY (co_id) REFERENCES public.cm_cos(id)");
          break;
      }
    }
  }

  public $job_schedulers = array(
    'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
    'co_id' => array('type' => 'integer', 'null' => true, 'default' => null),
    'job_type' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 128),
    'job_params' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 128),
    'failure_summary' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128),
    'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
    'indexes' => array(
      'PRIMARY' => array('unique' => true, 'column' => 'id')
    ),
    'tableParameters' => array()
  );

}