<?php echo $this->Html->link($this->Format->yesNo($ajaxToggle[$model][$field]), ['action' => 'toggle', $field, $ajaxToggle[$model]['id']], ['escape' => false]);
