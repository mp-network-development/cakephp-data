<?php
/**
 * @var \App\View\AppView $this
 */
?>
<div class="page index">
<h2><?php echo __('Addresses');?></h2>

<table class="table">
<tr>
	<th><?php echo $this->Paginator->sort('foreign_id');?></th>
	<th><?php echo $this->Paginator->sort('model');?></th>
	<th><?php echo $this->Paginator->sort('country_id');?></th>
	<th><?php echo $this->Paginator->sort('country_province_id');?></th>
	<th><?php echo $this->Paginator->sort('first_name');?></th>
	<th><?php echo $this->Paginator->sort('last_name');?></th>
	<th><?php echo $this->Paginator->sort('street');?></th>
	<th><?php echo $this->Paginator->sort('postal_code');?></th>
	<th><?php echo $this->Paginator->sort('city');?></th>
	<th><?php echo $this->Paginator->sort('lat');?></th>
	<th><?php echo $this->Paginator->sort('lng');?></th>
	<th><?php echo $this->Paginator->sort('last_used');?></th>
	<th><?php echo $this->Paginator->sort('formatted_address');?></th>
	<th class="actions"><?php echo __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($addresses as $address):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr>
		<td>
			<?php echo $this->Html->link($address['User']['id'], ['controller' => 'users', 'action' => 'view', $address['User']['id']]); ?>
		</td>
		<td>
			<?php echo h($address['model']); ?>
		</td>
		<td>
			<?php echo $this->Html->link($address['Country']['name'], ['controller' => 'countries', 'action' => 'view', $address['Country']['id']]); ?>
		</td>
		<td>
			<?php echo $this->Html->link($address['State']['name'], ['controller' => 'states', 'action' => 'view', $address['State']['id']]); ?>
		</td>
		<td>
			<?php echo h($address['first_name']); ?>
		</td>
		<td>
			<?php echo h($address['last_name']); ?>
		</td>
		<td>
			<?php echo h($address['street']); ?>
		</td>
		<td>
			<?php echo h($address['postal_code']); ?>
		</td>
		<td>
			<?php echo h($address['city']); ?>
		</td>
		<td>
			<?php echo h($address['lat']); ?>
		</td>
		<td>
			<?php echo h($address['lng']); ?>
		</td>
		<td>
			<?php echo $this->Time->niceDate($address['last_used']); ?>
		</td>
		<td>
			<?php echo h($address['formatted_address']); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link($this->Format->icon('view'), ['action' => 'view', $address['id']], ['escape' => false]); ?>
			<?php echo $this->Html->link($this->Format->icon('edit'), ['action' => 'edit', $address['id']], ['escape' => false]); ?>
			<?php echo $this->Form->postLink($this->Format->icon('delete'), ['action' => 'delete', $address['id']], ['escape' => false], __('Are you sure you want to delete # {0}?', $address['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>

<div class="pagination-container">
<?php echo $this->element('Tools.pagination'); ?></div>

</div>

<br/><br/>

<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('Add {0}', __('Address')), ['action' => 'add']); ?></li>
	</ul>
</div>
