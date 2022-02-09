<?php

class JobSchedulerConfig extends AppModel
{
    // Add behaviors
    public $actsAs = array('Containable',
    'Changelog' => array('priority' => 5));

    /**
     * @param Integer $co_id
     * @return array|null
     */

    public function getConfiguration($co_id)
    {

        // Get all the config data. Even the EOFs that i have now deleted
        $args = array();
        $args['conditions']['JobSchedulerConfig.co_id'] = $co_id;
        $args['conditions'][] = 'JobSchedulerConfig.job_scheduler_config_id IS NULL';
        $args['contain'] = false;
        $data = $this->find('first', $args);
        // There is no configuration available for the plugin. Abort
        if (empty($data)) {
            return null;
        }

        return $data;
    }

    // Validation rules for table elements
    public $validate = array(
        'co_id' => array(
            'rule' => 'numeric',
            'required' => true,
            'message' => 'A CO ID must be provided',
        ),
        'job_max_tries' => array(
            'rule' => 'notBlank',
            'required' => true,
            'allowEmpty' => false
        ),
    );
}
