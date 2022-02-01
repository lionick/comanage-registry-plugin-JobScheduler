<?php
App::uses('StandardController', 'Controller');

class JobSchedulersController extends StandardController
{
  // Class name, used by Cake
  public $name = 'JobSchedulers';
  
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
    'JobScheduler.JobScheduler',
    'Co',
  );

  /**
   * Obtain all Jobs
   *
   * @since Job Scheduler v1.0
   */

  function index() {  
    $jobs = $this->JobScheduler->find('all');
    $this->set('job_scheduler', $jobs);

  }
  
  /**
   * Delete a Job
   *
   * @since Job Scheduler v1.0
   * @param  mixed $id
   * @return void
   */
  public function delete($id) { 
      $this->JobScheduler->id = $id;
      $this->JobScheduler->delete();
      $this->Flash->set(_txt('er.deleted-a', array(filter_var($id,FILTER_SANITIZE_SPECIAL_CHARS))), array('key' => 'success'));
      $this->performRedirect();
     
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
    $p['index'] = ($roles['cmadmin'] || $roles['coadmin']);
    $p['delete'] = ($roles['cmadmin'] || $roles['coadmin']);

    $this->set('vv_permissions', $p);
    
    return($p[$this->action]);
  }

}