<?php echo $this->Html->link($this->Format->yesNo($ajaxToggle['MimeType']['active'], 'Active', 'Inactive', 1), ['action' => 'toggleActive', $ajaxToggle['MimeType']['id']], ['escape' => false]);
