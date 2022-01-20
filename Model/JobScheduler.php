<?php

App::import('Model', 'ConnectionManager');
App::uses('Security', 'Utility');
App::uses('Hash', 'Utility');

class JobScheduler extends AppModel
{
    // Required by COmanage Plugins
    public $cmPluginType = 'other';

    // Default display field for cake generated views
    public $displayField = 'name';

    // Add behaviors
    public $actsAs = array('Containable',
                           'Changelog' => array('priority' => 5));

    /**
     * Expose menu items.
     *
     * @ since COmanage Registry v3.1.x
     * @ return Array with menu location type as key and array of labels, controllers, actions as values.
     */

    public function cmPluginMenus()
    {
        $this->log(__METHOD__ . '::@', LOG_DEBUG);
        return array(
            'coconfig' => array(_txt('ct.job_schedulers.1') =>
            array(
                'controller' => 'job_schedulers',
                'action'     => 'index'
            )),
        );
    }

    /**
     * @param Integer $co_id
     * @return array|null
     */

    public function getConfiguration($co_id)
    {

        // Get all the config data. Even the EOFs that i have now deleted
        $args = array();
        $args['conditions']['JobScheduler.co_id'] = $co_id;

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
        'job_type' => array(
            'rule' => array(
                'inList',
                array(
                    JobSchedulerTypeEnum::Provision,
                    JobSchedulerTypeEnum::Sync
                )
            ),
            'required' => true
        ),
        'job_params' => array(
            'rule' => 'notBlank',
            'required' => true,
            'allowEmpty' => false
        ),
        'failure_summary' => array(
            'rule' => 'notBlank',
            'required' => false,
            'allowEmpty' => true
        ),
    );
}
