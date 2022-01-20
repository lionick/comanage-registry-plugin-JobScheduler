<?php
// $params = array('title' => _txt('fd.people', array($cur_co['Co']['name'])));
// print $this->element("pageTitle", $params);

// Add breadcrumbs
print $this->element("coCrumb");
$this->Html->addCrumb(_txt('ct.job_schedulers'));

?>

<div id="sorter" class="listControl">
  <?php print _txt('fd.sort.by'); ?>:
  <ul>
    <li class="spin"><?php print $this->Paginator->sort('id', _txt('fd.name')); ?></li>
    <li class="spin"><?php print $this->Paginator->sort('job_type', _txt('fd.status')); ?></li>
  </ul>
</div>

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
      Created
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
          print $job[0]['created'];
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

<?php print $this->element("pagination"); ?>
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