<?php $page=TCG\Voyager\Models\Post::first(); ?>
@can('browse',$page)
    Si puedes acceder
@else
    No puedes acceder
@endcan