<?php
/**
 * @var \App\View\AppView $this
 */
?>
<div class="page form">
<?php echo $this->Form->create('Currency');?>
	<fieldset>
		<legend><?php echo __('Edit {0}', __('Currency'));?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('code');
		echo $this->Form->input('symbol_left');
		echo $this->Form->input('symbol_right');
		echo $this->Form->input('decimal_places');
		echo $this->Form->input('value');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Form->postLink(__('Delete'), ['action' => 'delete', $this->Form->value('Currency.id')], ['escape' => false], __('Are you sure you want to delete # {0}?', $this->Form->value('Currency.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List {0}', __('Currencies')), ['action' => 'index']);?></li>
	</ul>
</div>