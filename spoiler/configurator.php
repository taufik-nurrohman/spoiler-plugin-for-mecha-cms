<form class="form-plugin" action="<?php echo $config->url_current; ?>/update" method="post">
  <?php $spoiler_config = File::open(PLUGIN . DS . basename(__DIR__) . DS . 'states' . DS . 'config.txt')->unserialize(); ?>
  <input name="token" type="hidden" value="<?php echo $token; ?>">
  <fieldset>
    <legend><?php echo $speak->plugin_spoiler_default_toggle_text->title; ?></legend>
    <label class="grid-group">
      <span class="grid span-1 form-label"><?php echo $speak->plugin_spoiler_default_toggle_text->label->open; ?></span>
      <span class="grid span-5"><input name="toggle_text_open" type="text" value="<?php echo Text::parse(Guardian::wayback('toggle_text_open', $spoiler_config['toggle_text_open']))->to_encoded_html; ?>"></span>
    </label>
    <label class="grid-group">
      <span class="grid span-1 form-label"><?php echo $speak->plugin_spoiler_default_toggle_text->label->close; ?></span>
      <span class="grid span-5"><input name="toggle_text_close" type="text" value="<?php echo Text::parse(Guardian::wayback('toggle_text_close', $spoiler_config['toggle_text_close']))->to_encoded_html; ?>"></span>
    </label>
  </fieldset>
  <fieldset>
    <legend><?php echo $speak->plugin_spoiler_default_toggle_placement->title; ?></legend>
    <div class="grid-group">
      <span class="grid span-1 form-label"><?php echo $speak->plugin_spoiler_default_toggle_placement->label->position; ?></span>
      <div class="grid span-5">
        <select name="toggle_placement">
        <?php

        $toggle_placement_options = array(
            'top' => $speak->plugin_spoiler_default_toggle_placement->value->top,
            'bottom' => $speak->plugin_spoiler_default_toggle_placement->value->bottom
        );

        foreach($toggle_placement_options as $k => $v) {
            echo '<option value="' . $k . '"' . (Guardian::wayback('toggle_placement', $spoiler_config['toggle_placement']) === $k ? ' selected' : "") . '>' . $v . '</option>';
        }

        ?>
        </select>
      </div>
    </div>
  </fieldset>
  <fieldset>
    <legend><?php echo $speak->plugin_spoiler_default_config->title; ?></legend>
    <label class="grid-group">
      <span class="grid span-1 form-label"><?php echo $speak->plugin_spoiler_default_config->label->skin; ?></span>
      <span class="grid span-5">
        <select name="skin">
        <?php

        $skin_options = glob(PLUGIN . DS . basename(__DIR__) . DS . 'shell' . DS . 'chromatophores' . DS . '*.css');

        sort($skin_options);

        foreach($skin_options as $skin) {
            $skin = basename($skin, '.css');
            echo '<option value="' . $skin . '"' . (Guardian::wayback('skin', $spoiler_config['skin']) === $skin ? ' selected' : "") . '>' . ucwords(Text::parse($skin)->to_text) . '</option>';
        }

        ?>
        </select>
      </span>
    </label>
    <label class="grid-group">
      <span class="grid span-1 form-label"><?php echo $speak->plugin_spoiler_default_config->label->css; ?></span>
      <span class="grid span-5"><textarea name="css" class="textarea-block code"><?php echo Text::parse(Guardian::wayback('css', $spoiler_config['css']))->to_encoded_html; ?></textarea></span>
    </label>
  </fieldset>
  <p><button class="btn btn-action" type="submit"><i class="fa fa-check-circle"></i> <?php echo $speak->update; ?></button></p>
</form>