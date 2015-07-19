<form class="form-plugin" action="<?php echo $config->url_current; ?>/update" method="post">
  <?php $spoiler_config = File::open(PLUGIN . DS . File::B(__DIR__) . DS . 'states' . DS . 'config.txt')->unserialize(); ?>
  <?php echo Form::hidden('token', $token); ?>
  <fieldset>
    <legend><?php echo $speak->plugin_spoiler_default_toggle_text->title; ?></legend>
    <label class="grid-group">
      <span class="grid span-1 form-label"><?php echo $speak->plugin_spoiler_default_toggle_text->label->open; ?></span>
      <span class="grid span-5"><?php echo Form::text('toggle_text_open', $spoiler_config['toggle_text_open']); ?></span>
    </label>
    <label class="grid-group">
      <span class="grid span-1 form-label"><?php echo $speak->plugin_spoiler_default_toggle_text->label->close; ?></span>
      <span class="grid span-5"><?php echo Form::text('toggle_text_close', $spoiler_config['toggle_text_close']); ?></span>
    </label>
  </fieldset>
  <fieldset>
    <legend><?php echo $speak->plugin_spoiler_default_toggle_placement->title; ?></legend>
    <div class="grid-group">
      <span class="grid span-1 form-label"><?php echo $speak->plugin_spoiler_default_toggle_placement->label->position; ?></span>
      <div class="grid span-5">
      <?php

      $toggle_placement_options = array(
          'top' => $speak->plugin_spoiler_default_toggle_placement->value->top,
          'bottom' => $speak->plugin_spoiler_default_toggle_placement->value->bottom
      );

      echo Form::select('toggle_placement', $toggle_placement_options, $spoiler_config['toggle_placement']);

      ?>
      </div>
    </div>
  </fieldset>
  <fieldset>
    <legend><?php echo $speak->plugin_spoiler_default_config->title; ?></legend>
    <label class="grid-group">
      <span class="grid span-1 form-label"><?php echo $speak->plugin_spoiler_default_config->label->skin; ?></span>
      <span class="grid span-5">
      <?php

      $skin = glob(PLUGIN . DS . File::B(__DIR__) . DS . 'assets' . DS . 'shell' . DS . 'pigment' . DS . '*.css', GLOB_NOSORT);
      $skin_options = array();
      foreach($skin as $s) {
          $s = File::N($s);
          $skin_options[$s] = ucwords(Text::parse($s, '->text'));
      }

      asort($skin_options);

      echo Form::select('skin', $skin_options, $spoiler_config['skin']);

      ?>
      </span>
    </label>
    <label class="grid-group">
      <span class="grid span-1 form-label"><?php echo $speak->plugin_spoiler_default_config->label->css; ?></span>
      <span class="grid span-5"><?php echo Form::textarea('css', $spoiler_config['css'], null, array('class' => array('textarea-block', 'code'))); ?></span>
    </label>
  </fieldset>
  <p><?php echo Jot::button('action', $speak->update); ?></p>
</form>