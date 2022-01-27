<?php
// $params = array('title' => _txt('fd.people', array($cur_co['Co']['name'])));
// print $this->element("pageTitle", $params);

// Add breadcrumbs
print $this->element("coCrumb");
$this->Html->addCrumb(_txt('ct.job_schedulers'));

?>

<table id="co_people" style="clear:both" class="population-index">
  <tr>
    <th>
      Id
    </th>
    <th>
      Type
    </th>
    <th>
      Job
    </th>
    <th>
      Failure Summary
    </th>
    <th>
      Tries
    </th>
    <th>
      Created
    </th>
    <th>
      Actions
    </th>
  </tr>
  <?php
  $i = 0;
  ?>
  <?php foreach ($job_scheduler as $job) : ?>
    <tr class="co-person line<?php print ($i % 2) + 1; ?>">

      <td>
        <span class="person-name">
          <?php
          print $job[0]['id'];
          ?>
        </span>
      </td>
      <td>
        <span class="person-email">
          <?php
          print $job[0]['job_type'];
          ?>
        </span>
      </td>
      <td>
        <span class="person-status">
          <?php
          print $job[0]['job_params'];
          ?>
      </td>
      <td>
        <span class="person-status">
          <?php
          print $job[0]['failure_summary'];
          ?>
      </td>
      <td>
        <span class="person-status">
          <?php
          print $job[0]['tries'];
          ?>
      </td>
      <td>
        <span class="person-status">
          <?php
          print $job[0]['created'];
          ?>
      </td>
      <td>
        <span class="action">
        <?php 
        
          if($vv_permissions['delete']) {
            print '<button type="button" class="deletebutton" title="' . _txt('op.delete')
              . '" onclick="javascript:js_confirm_generic(\''
              . _txt('js.remove') . '\',\''    // dialog body text
              . $this->Html->url(              // dialog confirm URL
                array(
                'plugin' => 'job_scheduler', // XXX can inflect from $vv_authenticator['Authenticator']['plugin']
                'controller' => 'job_schedulers',
                'action' => 'delete',
                $job[0]['id']
                )
              ) . '\',\''
              . _txt('op.remove') . '\',\''    // dialog confirm button
              . _txt('op.cancel') . '\',\''    // dialog cancel button
              . _txt('op.remove') . '\',[\''   // dialog title
              . filter_var(_jtxt($job[0]['job_params']),FILTER_SANITIZE_STRING)  // dialog body text replacement strings
              . '\']);">'
              . _txt('op.delete')
              . '</button>';
          }
        ?>
      </td>
    </tr>

<?php $i++; ?>
<?php endforeach; // $job_scheduler 
?>
</table>
<?php
if (empty($job_scheduler)) {
  // No search results, or there are no people in this CO
  print('<div id="noResults">' . _txt('rs.search.none') . '</div>');
  print('<div id="restoreLink">');
  $args = array();
  $args['plugin'] = null;
  $args['controller'] = 'job_scheduler';
  $args['action'] = 'index';
  $args['co'] = $cur_co['Co']['id'];
  print $this->Html->link(_txt('op.search.restore'), $args);
  print('</div>');
}
?>
<div class="clearfix"></div>

</div>

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