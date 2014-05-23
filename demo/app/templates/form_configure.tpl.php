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

		<label for="page">
			Page:
			<select id="page" name="page_id">
				<option>Select a Page</option>
				<? foreach ( $form->pages as $page ): ?>
					<option value="<?=$page->value ?>" <?=$page->selected ? 'selected':'' ?>><?=$page->text ?></option>
				<? endforeach ?>
			</select>
		</label>

		<label for="name">
			Name: <input type="text" id="name" name="name" value="<?=$form->name ?>">
		</label>

		<label for="cardinality">
			Cardinality: <input type="number" id="cardinality" name="cardinality" value="<?=$form->cardinality ?>"> (0 = unlimited)
		</label>
	</fieldset>

	<fieldset>
		<legend>Attributes</legend>
		<? foreach ( $form->attrs as $i => $attr ): ?>
			<fieldset class="<?=($attr->name) ? '' : 'new' ?>">
				<? if ( isset($attr->id) ): ?>
					<input type="hidden" id="attr_<?=$i ?>_id" name="attr[<?=$i ?>][id]" value="<?=$attr->id ?>">
				<? endif ?>

				<legend><?=($attr->name) ? $attr->name : 'New ' . ($i + 1) ?></legend>
				<label for="attr_<?=$i ?>_name">
					Name:
					<input type="text" id="attr_<?=$i ?>_name" name="attr[<?=$i ?>][name]" value="<?=$attr->name ?>">
				</label>
				<label for="attr_<?=$i ?>_show_markup">
					<input type="checkbox" id="attr_<?=$i ?>_show_markup" name="attr[<?=$i ?>][show_markup]" value="1" <?=($attr->show_markup)?'checked':'' ?>>
					Show Wordlet Markup
				</label>
				<label for="attr_<?=$i ?>_type">
					Input Type:
					<select id="attr_<?=$i ?>_type" name="attr[<?=$i ?>][type]">
						<option>Select Type</option>
						<? foreach ( $attr->types as $type ): ?>
							<option value="<?=$type->value ?>" <?=($type->selected)?'selected':''?>><?=$type->text ?></option>
						<? endforeach ?>
					</select>
				</label>
				<label for="attr_<?=$i ?>_html">
					HTML Conversion:
					<select id="attr_<?=$i ?>_html" name="attr[<?=$i ?>][html]">
						<option>Select HTML Conversion</option>
						<? foreach ( $attr->htmls as $html ): ?>
							<option value="<?=$html->value ?>" <?=($html->selected)?'selected':''?>><?=$html->text ?></option>
						<? endforeach ?>
					</select>
				</label>
				<label for="attr_<?=$i ?>_format">
					Format:
					<select id="attr_<?=$i ?>_format" name="attr[<?=$i ?>][format]">
						<option>Select Format</option>
						<? foreach ( $attr->formats as $format ): ?>
							<option value="<?=$format->value ?>" <?=($format->selected)?'selected':''?>><?=$format->text ?></option>
						<? endforeach ?>
					</select>
				</label>
				<label for="attr_<?=$i ?>_info">
					Info:
					<input id="attr_<?=$i ?>_info" name="attr[<?=$i ?>][info]" value="<?=$attr->info ?>">
				</label>
				<? if ( isset($attr->value) ): ?>
					<label for="attr_<?=$i ?>_value">
						Value:
						<textarea id="attr_<?=$i ?>_value" name="attr[<?=$i ?>][value]"><?=$attr->value ?></textarea>
					</label>
				<? endif ?>
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