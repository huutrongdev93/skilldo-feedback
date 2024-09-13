<?php
include 'roles.php';

Taxonomy::addPost(FEEDBACK_POST_TYPE,
    array(
        'labels' => array(
            'name'          => 'Feedback',
            'singular_name' => 'Feedback',
        ),
        'public' => false,
        'show_admin_column'  => false,
        'capabilities' => array(
            'view'      => 'view_posts_customer',
            'add'       => 'add_posts_customer',
            'edit'      => 'edit_posts_customer',
            'delete'    => 'delete_posts_customer',
        ),
    )
);

class AdminFeedback {
    static function navigation(): void {
        AdminMenu::add(FEEDBACK_POST_TYPE, 'Feedback', 'post?post_type='.FEEDBACK_POST_TYPE, [
            'position' => 'post',
            'icon' => '<img src="'.Path::plugin('feedback').'/assets/icon-customer.png" />'
        ]);
    }
    static function tableHeader($columns): array {
        $columnsNew['cb']   	= 'cb';
        $columnsNew['thumb'] 	= [
            'label' => 'Ảnh đại diện',
            'column' => fn($item, $args) => \SkillDo\Table\Columns\ColumnImage::make('image', $item, $args)->size(50)->circular()
        ];
        $columnsNew['title'] 	= [
            'label' => 'Họ và tên',
            'column' => fn($item, $args) => \SkillDo\Table\Columns\ColumnText::make('title', $item, $args)
        ];
        $columnsNew['content'] 	= [
            'label' => 'Chức vụ',
            'column' => fn($item, $args) => \SkillDo\Table\Columns\ColumnText::make('content', $item, $args)
        ];
        $columnsNew['excerpt'] 	= [
            'label' => 'Feedback',
            'column' => fn($item, $args) => \SkillDo\Table\Columns\ColumnText::make('excerpt', $item, $args)
        ];
        $columnsNew['action'] 	= 'Hành động';
        return $columnsNew;
    }
    static function formInput($form) {
        $info = $form->lang->group('info');
        foreach (Language::listKey() as $key) {
            $info->field[$key.'_content']['type'] = 'text';
            $info->field[$key.'_content']['label'] = 'Chức vụ';
        }
        $form->removeGroup(['seo', 'theme']);
        $info->renameField(['title' => 'Họ và Tên', 'excerpt' => 'Bình Luận']);
        return $form;
    }
    static function formSave($ins_data) {
        if(get_instance()->data['module'] == 'post' && Admin::getPostType() == FEEDBACK_POST_TYPE) {
            $ins_data['public'] = false;
        }
        return $ins_data;
    }
}
add_action('init', 'AdminFeedback::navigation');
add_filter('manage_post_'.FEEDBACK_POST_TYPE.'_columns', 'AdminFeedback::tableHeader');
add_filter('manage_post_'.FEEDBACK_POST_TYPE.'_input', 'AdminFeedback::formInput');
add_filter('save_object_before', 'AdminFeedback::formSave');