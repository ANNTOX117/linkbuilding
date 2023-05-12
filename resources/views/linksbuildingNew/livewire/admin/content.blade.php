@section('title')
    {{ $title }}
@endsection

<div>
<div class="content-wrapper center">
    @if(!empty($title))
        <div class="row page-title-header">
            <div class="col-12">
                <div class="page-header">
                    <h4 class="page-title">{{ $title }}</h4>
                </div>
            </div>
        </div>
    @endif
    <div class="topbar">
        <div class="left bold">
            <a data-toggle="modal" wire:click="create" data-backdrop="static" data-keyboard="false"><span class="add round btn-small reverse"><i class="fas fa-plus"></i> {{__('Add page')}}</span></a>
        </div>
    </div>
    <div class="cont ">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-templates">
                        <thead>
                        <tr>
                            <th>{{__('Title')}}</a></th>
                            <th>{{__('URL')}} </a></th>
                            <th>{{__('Actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($pages))
                            @foreach($pages as $page)
                                <tr>
                                    <td>{{ $page->title }}</td>
                                    <td>{{ $page->route }}</td>
                                    <td>
                                        <div class="text-center">
                                            <a class="blues" wire:click="edit({{ $page->id }})">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <a href="{{ route('pagebuilder.build', ['id' => $page->page_id, 'option' => true]) }}" class="blues" title="@lang('Preview')" target="_blank">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('pagebuilder.build', ['id' => $page->page_id]) }}" class="blues" title="@lang('Page builder')">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3">
                                    <div class="text-center text-muted mt-5 mb-5"><em>{{__('You don\'t have categories added yet')}}</em></div>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    @if(!empty($categories))
                        {{ $categories->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="create_page" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg mw-700" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('Create page')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="tx-10 tx-uppercase tx-medium tx-spacing-1 mg-b-5 tx-color-03">@lang('Title') <span class="tx-danger">*</span></label>
                        <input name="title" type="text" class="form-control" maxlength="140" placeholder="@lang('Enter a title')" autocomplete="off" wire:model.lazy="name">
                        @error('name') <p class="tx-danger mg-b-30">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="tx-10 tx-uppercase tx-medium tx-spacing-1 mg-b-5 tx-color-03">@lang('URL') <span class="tx-danger">*</span></label>
                        <input name="url" type="text" class="form-control" maxlength="140" placeholder="@lang('Enter a URL')" autocomplete="off" wire:model.lazy="url">
                        @error('url') <p class="tx-danger mg-b-30">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="tx-10 tx-uppercase tx-medium tx-spacing-1 mg-b-5 tx-color-03">@lang('SEO title')</label>
                        <input name="seo_title" type="text" class="form-control" maxlength="60" placeholder="@lang('Enter a SEO text')" autocomplete="off" wire:model.lazy="seo_title">
                        @error('seo_title') <p class="tx-danger mg-b-30">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="tx-10 tx-uppercase tx-medium tx-spacing-1 mg-b-5 tx-color-03">@lang('SEO description')</label>
                        <textarea name="seo_description" class="form-control" rows="3" placeholder="@lang('Enter a SEO text')" autocomplete="off" wire:model.lazy="seo_description"></textarea>
                        @error('seo_description') <p class="tx-danger mg-b-30">{{ $message }}</p> @enderror
                    </div>
                    <div class="inside-form mt-1 pb-0">
                        <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">@lang('Cancel')</button>
                    <button type="button" class="btn btn-primary" wire:click="doPage" wire:loading.remove>@lang('Save')</button>
                    <button type="button" class="btn btn-light" wire:loading wire:target="doPage"><i class="fas fa-spinner fa-spin"></i> @lang('Loading')...</button>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="edit_page" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg mw-700" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('Edit page')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="tx-10 tx-uppercase tx-medium tx-spacing-1 mg-b-5 tx-color-03">@lang('Title') <span class="text-danger">*</span></label>
                        <input name="title" type="text" class="form-control" maxlength="140" placeholder="@lang('Enter a title')" autocomplete="off" wire:model.lazy="name">
                        @error('name') <p class="tx-danger mg-b-30">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="tx-10 tx-uppercase tx-medium tx-spacing-1 mg-b-5 tx-color-03">@lang('URL') <span class="text-danger">*</span></label>
                        <input name="url" type="text" class="form-control" maxlength="140" placeholder="@lang('Enter a URL')" autocomplete="off" wire:model.lazy="url">
                        @error('url') <p class="tx-danger mg-b-30">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="tx-10 tx-uppercase tx-medium tx-spacing-1 mg-b-5 tx-color-03">@lang('SEO title')</label>
                        <input name="seo_title" type="text" class="form-control" maxlength="60" placeholder="@lang('Enter a SEO text')" autocomplete="off" wire:model.lazy="seo_title">
                        @error('seo_title') <p class="tx-danger mg-b-30">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="tx-10 tx-uppercase tx-medium tx-spacing-1 mg-b-5 tx-color-03">@lang('SEO description')</label>
                        <textarea name="seo_description" class="form-control" rows="3" placeholder="@lang('Enter a SEO text')" autocomplete="off" wire:model.lazy="seo_description"></textarea>
                        @error('seo_description') <p class="tx-danger mg-b-30">{{ $message }}</p> @enderror
                    </div>
                    <div class="inside-form mt-1 pb-0">
                        <small><em><sup><i class="fas fa-asterisk fa-xs text-danger"></i></sup> <apan class="text-muted">{{__('Required fields')}}</apan></em></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">@lang('Cancel')</button>
                    <button type="button" class="btn btn-primary" wire:click="doEditPage" wire:loading.remove>@lang('Save')</button>
                    <button type="button" class="btn btn-light" wire:loading wire:target="doEditPage"><i class="fas fa-spinner fa-spin"></i> @lang('Loading')...</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div wire:ignore.self class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{__('Confirm delete')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p>{{__('Are you sure want to delete this site?')}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">{{__('Close')}}</button>
                <button type="button" wire:click.prevent="delete" class="btn btn-danger close-modal" data-dismiss="modal">{{__('Yes, Delete')}}</button>
            </div>
        </div>
    </div>
</div>
</div>

@push('scripts')
    <script>
        var getUrlParameter = function getUrlParameter(sParam) {
            var sPageURL = window.location.search.substring(1),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                }
            }
        };

        // var updateOutput = function () {
        //     $('#nestable-output').val(JSON.stringify($('#nestable').nestable('serialize')));
        // };

        $(document).ready(function() {
            var opentab = getUrlParameter('opentab');

            if(typeof opentab !== 'undefined') {
                if(opentab == 'pages') {
                    $('#pages-tab').click()
                    replacestate();
                }
            }

            // $('#nestable').nestable({maxDepth: 2}).on('change', updateOutput);
            // updateOutput();
        });

        $(document).on('click', '.btn-save-menu', function() {
            let items = $('#nestable-output').val();
            @this.doOrder(items);
            // updateOutput();
        });

        $(function() {
            if($('#overview_menu').length) {
                $('#overview_menu').sortable({
                    cursor: 'move',
                    classes: {
                        'ui-sortable': 'highlight'
                    },
                    items: '> tr:not(.ui-state-skip)',
                    update: function (event, ui) {
                        var order_menu = [];
                        $('#overview_menu .ui-state-default').each(function (e) {
                            order_menu.push($(this).attr('data-info'));
                        });
                        var positions_menu = order_menu.join(',');
                        @this.overviewSort('menu', positions_menu);
                    }
                });
            }
        });

        window.addEventListener('onModal', event => {
            $(event.detail.modal).modal(event.detail.action);
        });

        window.addEventListener('doAlert', event => {
            dialogAlert(event.detail.message, event.detail.cancel);
        });

        window.addEventListener('doConfirm', event => {
            dialogConfirm(event.detail.message, event.detail.confirm, event.detail.cancel);
        });

        window.addEventListener('onError', event => {
            $(event.detail.modal).addClass('animated shake fast').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function (){ $(this).removeClass('animated shake fast'); });
        });

        window.addEventListener('onFlash', event => {
            setTimeout(function() {
                $('.flash-success').fadeOut(500, function() { $(this).remove(); });
            }, 3000);
        });

        window.addEventListener('onToggle', event => {
            if(event.detail.show) {
                $(event.detail.element).show();
            } else {
                $(event.detail.element).hide();
            }
        });

        window.addEventListener('onTags', event => {
            $(event.detail.element).tagsinput();
            $(event.detail.element).tagsinput('removeAll');
        });

        window.addEventListener('onEditTags', event => {
            $(event.detail.element).val(event.detail.content);
            $(event.detail.element).tagsinput();
        });

        window.addEventListener('onFile', event => {
            $(event.detail.element).text(event.detail.label);
        });

        window.addEventListener('onOrder', event => {
            if(event.detail.reload) {
                // $('#nestable').nestable('destroy');
                // $('#nestable').nestable({maxDepth: 2}).on('change', updateOutput);
                // updateOutput();
            }
        });

        window.addEventListener('onEditor', event => {
            if($(event.detail.element + ' .ql-editor').length === 0) {
                Quill.register("modules/htmlEditButton", htmlEditButton);
                var editor = new Quill(event.detail.element, {
                    modules: {
                        toolbar: [
                            ['bold', 'italic'],
                            ['link', 'blockquote', 'image'],
                            [{ list: 'ordered' }, { list: 'bullet' }]
                        ],
                        htmlEditButton: {
                            debug: true,
                            msg: "@lang('Edit the content in HTML format')",
                            okText: "@lang('Save HTML')",
                            cancelText: "@lang('Cancel')",
                            buttonHTML: "<i class=\"fas fa-code tx-black\"></i>",
                            buttonTitle: "@lang('Show HTML source')",
                            syntax: false,
                            prependSelector: 'div#myelement',
                            editorModules: {}
                        }
                    },
                    theme: 'snow'
                });

                editor.on('editor-change', function(e) {
                    if(e === 'text-change') {
                        @this.doContent(editor.root.innerHTML);
                    }
                });
            } else {
                $(event.detail.element + ' .ql-editor').html('');
            }
        });

        window.addEventListener('onEditorEdit', event => {
            if($(event.detail.element + ' .ql-editor').length === 0) {
                Quill.register("modules/htmlEditButton", htmlEditButton);
                var editor_edit = new Quill(event.detail.element, {
                    modules: {
                        toolbar: [
                            ['bold', 'italic'],
                            ['link', 'blockquote', 'image'],
                            [{ list: 'ordered' }, { list: 'bullet' }]
                        ],
                        htmlEditButton: {
                            debug: true,
                            msg: "@lang('Edit the content in HTML format')",
                            okText: "@lang('Save HTML')",
                            cancelText: "@lang('Cancel')",
                            buttonHTML: "<i class=\"fas fa-code tx-black\"></i>",
                            buttonTitle: "@lang('Show HTML source')",
                            syntax: false,
                            prependSelector: 'div#myelement',
                            editorModules: {}
                        }
                    },
                    theme: 'snow'
                });

                $(event.detail.element + ' .ql-editor').html(event.detail.content);

                editor_edit.on('editor-change', function(e) {
                    if(e === 'text-change') {
                        @this.doContent(editor_edit.root.innerHTML);
                    }
                });
            } else {
                $(event.detail.element + ' .ql-editor').html('');
                $(event.detail.element + ' .ql-editor').html(event.detail.content);
            }
        });

        $(document).on('change', '.switch_menu_option', function() {
            var option = $(this).val();
            var value  = $(this).is(':checked');
            var name   = $(this).data('name');

            @this.doMenuOption(option, value, name);
        });

        $(document).on('change', '.switch_page_option', function() {
            var option = $(this).val();
            var value  = $(this).is(':checked');
            var name   = $(this).data('name');

            @this.doPageOption(option, value, name);
        });

        $(document).on('change', '.switch_blog_option', function() {
            var option = $(this).val();
            var value  = $(this).is(':checked');
            var name   = $(this).data('name');

            @this.doBlogOption(option, value, name);
        });

        $(document).on('change', '.is_tags', function() {
            var tags = $(this).val();
            var name = $(this).data('name');

            @this.doTags(name, tags);
        });

        $(document).on('change', 'select[name="dropdown-site"]', function() {
            let site = $(this).val();
            @this.doSite(site);
        });

        function replacestate() {
            var uri = window.location.href.toString();
            if(uri.indexOf("?") > 0) {
                var clean_uri = uri.substring(0, uri.indexOf("?"));
                window.history.replaceState({}, document.title, clean_uri);
            }
        }

        function doRemove() {
            @this.doDelete();
        }

    </script>
@endpush
