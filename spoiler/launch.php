<?php

$spoiler_config = File::open(PLUGIN . DS . basename(__DIR__) . DS . 'states' . DS . 'config.txt')->unserialize();

Config::set('spoiler_id', 1);

Filter::add('shortcode', function($content) use($spoiler_config) {

    // No shortcode pattern found
    if(strpos($content, '{{spoiler') === false) return $content;

    $config = Config::get();
    $counter = 0;

    $content = preg_replace_callback('#(?<!`)\{\{spoiler(\.([a-zA-Z0-9\-]+))?(\}\}| +.*?\}\})(?!`)([\s\S]*?)(?<!`)\{\{\/spoiler(\.\2)?\}\}(?!`)#', function($matches) use($config, $spoiler_config, &$counter) {

        $counter++;

        // Fix all shortcode names with descendant definition. The opening and closing tag must be the same.
        // `{{spoiler}} ... {{/spoiler}}` and `{{spoiler.default}} ... {{/spoiler.default}}` are OK
        // but not for `{{spoiler.default}} ... {{/spoiler}}`. It will never be treated as a valid shortcode
        // pattern just because the end closing of the shortcode is not `{{/spoiler.default}}`
        if( ! empty($matches[2])) {
            $matches[0] = preg_replace('#\{\{\/spoiler\}\}$#', '{{/spoiler.' . $matches[2] . '}}', $matches[0]);
        }

        // Extract shortcode attributes
        $data = Converter::attr($matches[0], array('{{', '}}', ' '), array('"', '"', '='));

        // Put all attributes to the HTML output
        $attrs = $data['attributes'];
        $attrs_str  = ' class="spoiler spoiler-' . ( ! empty($matches[2]) ? $matches[2] : 'default') . ' spoiler-state-';
        $attrs_str .= isset($attrs['expand']) && $attrs['expand'] !== false ? 'expanded' : 'collapsed';
        $attrs_str .= isset($attrs['lock']) && $attrs['lock'] !== false ? ' spoiler-state-disabled' : "";
        $attrs_str .= isset($attrs['class']) ? ' ' . $attrs['class'] . ' p"' : ' p"';
        $attrs_str .= ' id="' . (isset($attrs['id']) ? $attrs['id'] : 'spoiler:' . ($config->spoiler_id . '-' . $counter)) . '"';
        $attrs_str .= ' data-toggle-text="' . (isset($attrs['toggle-text']) ? $attrs['toggle-text'] : $spoiler_config['toggle_text_open'] . ' | ' . $spoiler_config['toggle_text_close']) . '"';
        $attrs_str .= ' data-toggle-placement="' . (isset($attrs['toggle-placement']) ? $attrs['toggle-placement'] : $spoiler_config['toggle_placement']) . '"';
        $attrs_str .= isset($attrs['connect']) ? ' data-connection="' . $attrs['connect'] . '"' : "";

        // Other attributes will be treated as normal HTML attributes
        if(is_array($attrs)) {
            unset($attrs['expand']);
            unset($attrs['lock']);
            unset($attrs['class']);
            unset($attrs['id']);
            unset($attrs['toggle-text']);
            unset($attrs['toggle-placement']);
            unset($attrs['connect']);
            foreach($attrs as $key => $value) {
                $attrs_str .= ' ' . $key . '="' . $value . '"';
            }
        }

        return O_BEGIN . '<div' . $attrs_str . '>' . NL . '<div markdown="1" class="spoiler-content">' . $data['content'] . '</div>' . NL . '</div>' . O_END;

    }, $content);

    Config::set('spoiler_id', $config->spoiler_id + 1);

    return $content;

}, 9);

Filter::add('content', function($content) {
    // Remove the `markdown` attribute if it's still there
    return str_replace(' markdown="1" class="spoiler-content"', ' class="spoiler-content"', $content);
}, 9);

Weapon::add('SHIPMENT_REGION_TOP', function() {
    echo O_BEGIN . str_repeat(TAB, 2) . '<script>document.documentElement.className += \' spoiler-js\';</script>' . O_END;
});

Weapon::add('shell_after', function() use($spoiler_config) {
    echo Asset::stylesheet('cabinet/plugins/' . basename(__DIR__) . '/shell/spoiler.css');
});

Weapon::add('SHIPMENT_REGION_BOTTOM', function() {
    echo Asset::javascript('cabinet/plugins/' . basename(__DIR__) . '/sword/spoiler.js');
});