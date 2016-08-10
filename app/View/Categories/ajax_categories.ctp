<?php
function nested2ul($data) {
  $result = array();

  if (sizeof($data) > 0) {
    $result[] = '<div class="panel-group" id="accordion_category">';
    foreach ($data as $entry) {
      $result[] = sprintf(
        '<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_%s">%s</a>
            </h4>
        </div>
        <div id="collapse_%s" class="panel-collapse collapse">
            <div class="panel-body">
                %s
            </div>
        </div>
    </div>',
        md5($entry['Category']['id']),
        $entry['Category']['name'],
        md5($entry['Category']['id']),
        nested2ul($entry['children'])
      );
    }
    $result[] = '</div>';
  }

  return implode($result);
}

echo nested2ul($categories);
?>
<div class="form-group"><input type="button" id="category_choosed" value="Done"></div>
<style type="text/css">
#accordion_category {
    max-height: 400px;
    overflow: auto;
}
.selected_category {
    background-color: #337ab7 !important;
    color: #fff !important;
}
</style>