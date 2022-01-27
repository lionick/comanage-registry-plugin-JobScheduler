<?php
App::uses('StandardController', 'Controller');

class JobSchedulerConfigsController extends StandardController
{
  // Class name, used by Cake
  public $name = 'JobSchedulerConfigs';
  
   /**
  * By default a new CSRF token is generated for each request, and each token can only be used once.
  * If a token is used twice, the request will be blackholed. Sometimes, this behaviour is not desirable,
  * as it can create issues with single page applications.
   */

  public $components = array(
    'RequestHandler',
    'Security' => array(
      'csrfUseOnce' => false,
      'csrfExpires' => '+10 minutes'
  ));
  
  // This controller needs a CO to be set
  public $requires_co = true;
  
  // When using additional models, we must also specify our own
  public $uses = array(
    'JobScheduler.JobSchedulerConfig',
    'Co',
  );

    
  /**
   * Edit JobSchedulerConfigs Settings
   *
   * @param integer $id
   * @return void
   */
  public function edit($id=null) {
    //Get data if any for the configuration of RciamStatsViewer  
    $configData = $this->JobSchedulerConfig->getConfiguration($this->cur_co['Co']['id']);
    $id = isset($configData['JobSchedulerConfig']) ? $configData['JobSchedulerConfig']['id'] : -1;
    
    if($this->request->is('post')) {
      // We're processing an update
      // if i had already set edit before, now retrieve the entry and update
      if($id > 0){
        $this->RciamStatsViewer->id = $id;
        $this->request->data['JobSchedulerConfig']['id'] = $id;
      }
      
      try {
        
        $save_options = array(
          'validate'  => true,
        );
        
        if($this->JobSchedulerConfig->save($this->request->data, $save_options)){
          $this->Flash->set(_txt('rs.saved'), array('key' => 'success'));
        } else {
          $invalidFields = $this->JobSchedulerConfig->invalidFields();
          $this->log(__METHOD__ . '::exception error => ' . print_r($invalidFields, true), LOG_DEBUG);
          $this->Flash->set(_txt('rs.job_scheduler_config.error'), array('key' => 'error'));
        }
      }
      catch(Exception $e) {
        $this->log(__METHOD__ . '::exception error => ' .$e, LOG_DEBUG);
        $this->Flash->set($e->getMessage(), array('key' => 'error'));
      }
      // Redirect back to a GET
      $this->redirect(array('action' => 'edit', 'co' => $this->cur_co['Co']['id']));
    } else {
      // Return the existing data if any
      $this->set('vv_job_scheduler_configs', $configData);
    }
  }

  /**
   * For Models that accept a CO ID, find the provided CO ID.
   * - precondition: A coid must be provided in $this->request (params or data)
   *
   * @since  COmanage Registry v3.1.x
   * @return Integer The CO ID if found, or -1 if not
   */

  public function parseCOID($data = null) {
    if($this->action == 'edit'
      ) {
      if(isset($this->request->params['named']['co'])) {
        return $this->request->params['named']['co'];
      }
    }
    
    return parent::parseCOID();
  }

  /**
   * Authorization for this Controller, called by Auth component
   * - precondition: Session.Auth holds data used for auth decisions
   * - postcondition: $permissions set with calculated permissions
   *
   * @since  COmanage Registry v3.1.x
   * @return Array Permissions
   */

  function isAuthorized() {
    $this->log(__METHOD__ . '::@', LOG_DEBUG);
    $roles = $this->Role->calculateCMRoles();
  
    // Construct the permission set for this user, which will also be passed to the view.
    $p = array();
  
    // Determine what operations this user can perform
    $p['edit'] = ($roles['cmadmin'] || $roles['coadmin']);
    $this->set('vv_permissions', $p);
    
    return($p[$this->action]);
  }
}