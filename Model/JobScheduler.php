<?php

class JobScheduler extends AppModel
{
    // Required by COmanage Plugins
    public $cmPluginType = 'other';

     // Association rules from this model to other models
    public $belongsTo = array("Co");  // A Job Scheduler is attached to one CO

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
                'controller' => 'job_scheduler_configs',
                'action'     => 'edit'
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


    /**
     * Add Job Scheduler Functionality
     *  
     * @param  mixed $pluginTarget
     * @param  mixed $pluginModel
     * @param  mixed $provisioningData
     * @return boolean
     */

    public function addJobScheduler($pluginTarget, $pluginModel, $provisioningData)
    {
        $group = false;
        $person = false;
        $co_id = null;
        // Determine co_id
        if(!empty($provisioningData["CoGroup"]["co_id"])) {
            $co_id = $provisioningData["CoGroup"]["co_id"];
        }
        else if(!empty($provisioningData["CoPerson"]["co_id"])) {
            $co_id = $provisioningData["CoPerson"]["co_id"];
        }
        // Add background jobs only for CoPerson/ CoGroup for now
        if(!empty($co_id) && $this->Co->CoSetting->backgroundJobEnabled($co_id) && (!empty($provisioningData['CoGroup']['id']) || !empty($provisioningData['CoPerson']['id']))) {

            if(!empty($provisioningData['CoGroup']['id'])) {
                $group = true;
                $provisionGroup='CoGroup';
            }       
            if(!empty($provisioningData['CoPerson']['id'])) {
                $person = true;
                $provisionPerson='CoPerson';
            }
            if($group == true) {
                $this->save(array('id' => null, 'co_id' => $provisioningData["CoGroup"]["co_id"], 'job_type' => JobSchedulerTypeEnum::Provision, 'job_params' => 'provisioner '. $pluginTarget[$pluginModel->name]["co_provisioning_target_id"] . ' ' .  $provisionGroup . ' ' . $provisioningData['CoGroup']['id'], 'failure_summary' => '', 'tries' => 0, 'created' => date('Y-m-d H:i:s')), false);
            }
            if($person == true) {
                $this->save(array('id' => null, 'co_id' => $provisioningData["CoPerson"]["co_id"], 'job_type' => JobSchedulerTypeEnum::Provision, 'job_params' => 'provisioner '. $pluginTarget[$pluginModel->name]["co_provisioning_target_id"] . ' ' .  $provisionPerson . ' ' . $provisioningData['CoPerson']['id'], 'failure_summary' => '', 'tries' => 0, 'created' => date('Y-m-d H:i:s')), false);
            }
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * getActiveJobs
     *
     * @param  mixed $co_id
     * @param  mixed $max_tries
     * @return array
     */
    
    public function getActiveJobs($co_id, $max_tries) {
        $args = array();
        $args['conditions']['JobScheduler.co_id'] = $co_id;
        $args['conditions'][] = 'JobScheduler.tries < ' . $max_tries;
        $activeJobs = $this->find('all', $args);
        return $activeJobs;
    }
    
}
