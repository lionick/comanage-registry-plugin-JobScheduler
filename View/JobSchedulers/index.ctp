<?php
print $this->Html->css('JobScheduler.style', array('inline' => false));
print $this->element("coCrumb");
// Add breadcrumbs
$crumbTxt = _txt('ct.job_scheduler_configs.1');
$args['plugin'] = 'job_scheduler';
$args['controller'] = 'job_scheduler_configs';
$args['action'] = 'edit';
$args['co'] = $cur_co['Co']['id'];
$this->Html->addCrumb($crumbTxt, $args);
$this->Html->addCrumb(_txt('ct.job_schedulers'));

?>

<table id="co_people" style="clear:both" class="population-index">
  <thead>
    <tr>
      <th><?php print(_txt('fd.job_scheduler.id')); ?></th>
      <th><?php print(_txt('fd.job_scheduler.type')); ?></th>
      <th><?php print(_txt('fd.job_scheduler.params')); ?></th>
      <th><?php print(_txt('fd.job_scheduler.data')); ?></th>
      <th><?php print(_txt('fd.job_scheduler.failure_summary')); ?></th>
      <th><?php print(_txt('fd.job_scheduler.tries')); ?></th>
      <th><?php print(_txt('fd.job_scheduler.created')); ?></th>
      <th><?php print(_txt('fd.actions')); ?></th>
    </tr>
  </thead>
  <?php
  $i = 0;
  ?>
  <?php foreach ($job_scheduler as $job) : ?>
    <tr class="co-person line<?php print ($i % 2) + 1; ?>">

      <td>
        <span>
          <?php
          print $job['JobScheduler']['id'];
          ?>
        </span>
      </td>
      <td>
        <span>
          <?php
          print JobSchedulerTypeEnum::type[$job['JobScheduler']['job_type']];
          ?>
        </span>
      </td>
      <td>
        <span>
          <?php
          print $job['JobScheduler']['job_params'];
          ?>
      </td>
      <td>
        <span>
          <?php
          print $job['JobScheduler']['job_data'];
          ?>
      </td>
      <td>
        <span>
          <?php
          print $job['JobScheduler']['failure_summary'];
          ?>
      </td>
      <td>
        <span>
          <?php
          print $job['JobScheduler']['tries'];
          ?>
      </td>
      <td>
        <span>
          <?php
          print $job['JobScheduler']['created'];
          ?>
      </td>
      <td>
        <span class="action">
          <?php

          if ($vv_permissions['delete']) {
            print '<button type="button" class="deletebutton" title="' . _txt('op.delete')
              . '" onclick="javascript:js_confirm_generic(\''
              . _txt('js.remove') . '\',\''    // dialog body text
              . $this->Html->url(              // dialog confirm URL
                array(
                  'plugin' => 'job_scheduler', // XXX can inflect from $vv_authenticator['Authenticator']['plugin']
                  'controller' => 'job_schedulers',
                  'action' => 'delete',
                  $job['JobScheduler']['id']
                )
              ) . '\',\''
              . _txt('op.remove') . '\',\''    // dialog confirm button
              . _txt('op.cancel') . '\',\''    // dialog cancel button
              . _txt('op.remove') . '\',[\''   // dialog title
              . filter_var(_jtxt($job['JobScheduler']['job_params']), FILTER_SANITIZE_STRING)  // dialog body text replacement strings
              . '\']);">'
              . _txt('op.delete')
              . '</button>';
          }
          ?>
      </td>
    </tr>
    <?php
      $i++;
    ?>
  <?php endforeach; // $job_scheduler 
  ?>
</table>
<?php
if (empty($job_scheduler)) {
  // No jobs found
  print('<div id="noResults">' . _txt('rs.search.none') . '</div>');
  print('<div id="restoreLink">');
  $args = array();
  $args['plugin'] = 'job_scheduler';
  $args['controller'] = 'job_schedulers';
  $args['action'] = 'index';
  $args['co'] = $cur_co['Co']['id'];
  print $this->Html->link(_txt('op.search.restore'), $args);
  print('</div>');
}
?>

<script>
  // Prevents propagations of event handling up to containers
  function noprop(e) {
    if (!e)
      e = window.event;

    //IE9 & Other Browsers
    if (e.stopPropagation) {
      e.stopPropagation();
    }
    //IE8 and Lower
    else {
      e.cancelBubble = true;
    }
  }
</script>