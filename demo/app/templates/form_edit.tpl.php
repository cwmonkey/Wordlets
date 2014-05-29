<style>
label {
	display: block;
	margin: .5em 0;
	vertical-align: top;
}

form {
	display: inline-block;
}

.new + .new {
	display: none;
}
</style>

<form action="<?=$form->action ?>" method="post">
	<fieldset>
		<? if ( isset($form->id) ): ?>
			<input type="hidden" name="id" value="<?=$form->id ?>">
		<? endif ?>

		<legend>Values</legend>
		<? foreach ( $form->values as $iv => $value ): ?>
			<fieldset class="<?=($value) ? '' : 'new' ?>">
				<? foreach ( $form->attrs as $ia => $attr ): ?>
					<label for="value_<?=$iv ?>_<?=$ia ?>">
						<? if ( $attr->type == 'bool' ): ?>
							<input id="value_<?=$iv ?>_<?=$ia ?>" type="checkbox" value="1" name="value[<?=$iv ?>][<?=@$attr->name ?>][value]" <?=@$value->{$attr->name} ? 'checked' : '' ?>>
						<? endif ?>

						<?=$attr->name ?>:

						<? if ( $attr->type == 'single' ): ?>
							<input id="value_<?=$iv ?>_<?=$ia ?>" type="text" value="<?=@$value->{$attr->name} ?>" name="value[<?=$iv ?>][<?=@$attr->name ?>][value]">
						<? elseif ( $attr->type == 'number' ): ?>
							<input id="value_<?=$iv ?>_<?=$ia ?>" type="number" value="<?=@$value->{$attr->name} ?>" name="value[<?=$iv ?>][<?=@$attr->name ?>][value]">
						<? elseif ( $attr->type == 'multi' ): ?>
							<textarea id="value_<?=$iv ?>_<?=$ia ?>" name="value[<?=$iv ?>][<?=@$attr->name ?>][value]"><?=@$value->{$attr->name} ?></textarea>
						<? endif ?>
					</label>
				<? endforeach ?>
			</fieldset>
		<? endforeach ?>
	</fieldset>
	<fieldset>
		<input type="submit" value="<?=$form->SaveText ?>">
	</fieldset>
</form>

<script type="text/javascript" defer src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script defer>window.jQuery || document.write('<script defer src="jquery-1.7.2.min.js"><\/script>')</script>
<script type="text/javascript" defer src="script-configure.js"></script>