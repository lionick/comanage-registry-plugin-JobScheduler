<!--
/**
 * COmanage Registry Job Scheduler Plugin Fields
 *
 * Portions licensed to the University Corporation for Advanced Internet
 * Development, Inc. ("UCAID") under one or more contributor license agreements.
 * See the NOTICE file distributed with this work for additional information
 * regarding copyright ownership.
 *
 * UCAID licenses this file to you under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with the
 * License. You may obtain a copy of the License at:
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 * @link          http://www.internet2.edu/comanage COmanage Project
 * @package       registry-plugin
 * @since         COmanage Registry v3.1.x
 * @license       Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */
-->
<?php
  // Determine if fields are editable
  $e = false;
  
  if($this->action == "edit" && $vv_permissions['edit'])
    $e = true;
    
  // We shouldn't get here if we don't have at least read permission, but check just in case
  if(!$e && !$vv_permissions['view'])
    return false;
  
  // Add breadcrumbs
  print $this->element("coCrumb");

  $model = $this->name;
  $controller = Inflector::underscore(Inflector::pluralize($model));

  $crumbTxt = _txt('ct.job_scheduler_configs.1');
  $this->Html->addCrumb($crumbTxt);
  
  print $this->Form->hidden('co_id', array('default' => $cur_co['Co']['id'])) . "\n";
  // Store the token
  $token_key = $this->request->params['_Token']['key'];

?>
<div id="sorter" class="listControl">
  <ul>
    <li class="spin">
      <?php
      print $this->Html->link(
        _txt('ct.job_schedulers'),
        array(
          'controller' => 'job_schedulers',
          'action' => 'index',
          'co' => $this->params['named']['co'],
        ),
        array('class' => 'notebutton', 'style' => 'border:unset')
      );
      ?>
    </li>
  </ul>
</div>
<div id="<?php print $this->action; ?>_job_scheduler_configs" class="explorerContainer">
    <div id="jobSchedulerConfigs" class="personExplorer">
        <ul class="fields form-list form-list-admin">         
          <li>
            <div class="field-name">
              <div class="field-title">
                <?php print $this->Form->label('job_max_tries', _txt('pl.job_scheduler_config.job_max_tries')); ?>
                <span class="required">*</span>
              </div>
              <span class="field-descr"><?php print _txt('pl.job_scheduler_config.job_max_tries.desc'); ?></span>
            </div>
            <div class="field-info">
              <?php
              $value = empty($vv_job_scheduler_configs['JobSchedulerConfig']['job_max_tries']) ? ''
                      : filter_var($vv_job_scheduler_configs['JobSchedulerConfig']['job_max_tries'], FILTER_SANITIZE_SPECIAL_CHARS);
              print $this->Form->input('job_max_tries', array('size' => 2, 'value' => $value));
              ?>
            </div>
          </li>
          <?php if($e): ?>
              <li class="fields-submit">
                  <div class="field-name">
                      <span class="required"><?php print _txt('fd.req'); ?></span>
                  </div>
                  <div class="field-info">
                    <?php print $this->Form->submit($submit_label); ?>
                  </div>
              </li>
          <?php endif; ?>      
        </ul>
    </div> <!-- personExplorer -->
</div> <!-- explorerContainer -->
