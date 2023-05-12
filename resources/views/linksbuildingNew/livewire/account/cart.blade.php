<div class="container-fluid">
    @if($view == 'notification')
        <div class="cart-notification">
            <div class="table-title">
                <h1>{{ $notify_title }}</h1>
            </div>
            <div class="white-container mt-5">
                {!! $notify_description !!}
            </div>
            <div class="row mt-2 mb-3">
                @if($verify)
                    <div class="col-12">
                        <div class="verification-container">
                            <h2>{{__('Verification code')}}</h2>
                            <p class="text-muted">
                                {{__('You can sign these terms and conditions of payments writing the 6 digits that we have sent you to your email')}}
                            </p>
                            <div class="code-container">
                                <input wire:model="numbers.0" type="number" class="code" placeholder="0" min="0" max="9" maxlength="1" required />
                                <input wire:model="numbers.1" type="number" class="code" placeholder="0" min="0" max="9" maxlength="1" required />
                                <input wire:model="numbers.2" type="number" class="code" placeholder="0" min="0" max="9" maxlength="1" required />
                                <input wire:model="numbers.3" type="number" class="code" placeholder="0" min="0" max="9" maxlength="1" required />
                                <input wire:model="numbers.4" type="number" class="code" placeholder="0" min="0" max="9" maxlength="1" required />
                                <input wire:model="numbers.5" type="number" class="code" placeholder="0" min="0" max="9" maxlength="1" required />
                            </div>
                            @if($status == 'redirect')
                                <small class="redirect">{{__('Correct security code, redirecting to the payment page')}}...</small>
                            @elseif($status == 'error')
                                <small class="error">{{__('The security code is incorrect, please check again to continue')}}</small>
                            @else
                                <div class="re-sent-message mb-2" style="display: none">
                                    <small class="warning">{{__('The code has been re-sent to your email')}}</small>
                                </div>
                                <small class="verification_text info">{{__('Once you enter a valid security code, you will be redirected to make your payment')}}. {{__('Did not you get anything?')}} <a href="javascript:void(0)" wire:click="doResend">{{__('Resend code')}}</a></small>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="col-6">
                        <a href="javascript:void(0)" wire:click="doDisagree" class="btn btn-secondary btn-block mt-4 mb-2">{{__('Cancel')}}</a>
                    </div>
                    <div class="col-6">
                        <a href="javascript:void(0)" wire:click="doVerify" wire:loading.remove class="btn btn-primary btn-block mt-4 mb-2">{{__('I agree, continue')}}</a>
                        <div class="inline-block w-100" wire:loading wire:target="doVerify">
                            <a href="javascript:void(0)" class="btn btn-primary btn-loading btn-block mt-4 mb-2"><i class="fas fa-spinner fa-spin"></i> {{__('Please wait')}}...</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="cart-details support-list bg-white p-3 shadow">
            <div class="table-title d-flex flex-column flex-lg-row justify-content-between p-3">
                <h4 class="m-0 p-0 my-4 text-center text-lg-left font-weight-bold">{{__('Shopping cart')}}</h4>
                @if($cart->isNotEmpty())<p class="text-muted table-subtitle m-0 text-center text-lg-right">{{ $products }} {{__('items in your cart')}}</p>@endif
            </div>
            @if(\Session::has('error'))
                <div class="alert alert-danger">{{ \Session::get('error') }}</div>
                {{ \Session::forget('error') }}
            @endif
            @if($cart->isNotEmpty())
                <div class="row my-3 justify-content-center">
                    <div class="col-auto col-lg-3 form-group mb-0">
                        <select id="perpage" class="form-control d-inline-block select-small inputs mr-2" wire:change="perpage($event.target.value)">
                            <option value="15" selected>15 {{__('per page')}}</option>
                            <option value="50">50 {{__('per page')}}</option>
                            <option value="100">100 {{__('per page')}}</option>
                            <option value="500">500 {{__('per page')}}</option>
                            <option value="1000">1000 {{__('per page')}}</option>
                        </select>
                    </div>
                    <div class="col-12 col-lg-3 ml-auto mt-2 mt-lg-0">
                        <input type="text" class="form-control inputs" id="search" placeholder="{{__('Search')}}..." wire:model.lazy="search" />
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-default">
                        <thead>
                        <tr>
                            <th>{{__('Product')}} <a wire:click="sort('item')" class="sort"><i class="fas fa-sort"></i></a></th>
                            <th >{{ __('Years') }}</th>
                            <th class="price">{{__('Price')}} <a wire:click="sort('price')" class="sort"><i class="fas fa-sort"></i></a></th>
                            <th class="actions"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($cart as $item)
                            @php
                                $details_item = json_decode($item->details);
                            @endphp
                            <tr @if(array_key_exists($item->id, $show) and $show[$item->id] == 'show') class="opened" @endif>
                                <td>
                                    @if($item->item == 'packages')
                                        {{ $item->type }}: {{ $item->name }}
                                        <p class="text-muted">{{ $item->text }}</p>
                                    @else
                                        <p class="text-ucfirst">{{ trans($item->item) }} @if(intval($item->requested) == 1) ({{__('Requested')}}) @endif</p>
                                    @endif

                                    @if(array_key_exists($item->id, $show) and $show[$item->id] == 'show')
                                        <button href="javascript:void(0)" wire:click="package('{{$item->item}}', {{$item->id}}, 'hide', {{intval($item->requested)}})" id="hide_details_{{$item->id}}" class="text-muted">{{__('Hide details')}} <i class="fas fa-caret-up"></i></button>
                                    @else
                                        <button href="javascript:void(0)" wire:click="package('{{$item->item}}', {{$item->id}}, 'show', {{intval($item->requested)}})" id="show_details_{{$item->id}}" class="text-muted">{{__('Show details')}} <i class="fas fa-caret-down"></i></button>
                                    @endif
                                </td>
                                <td>{{ @$details_item->years == -5 ? 'Permanent link' : @$details_item->years }}</td>
                                <td>{{ currency() }} {{ get_price($item->price) }}</td>
                                <td class="justify-content-center align-self-center edit-remove-item">
                                    @if($item->id == $edit)
                                        <button wire:click="modifiedPackage({{$item->id}}, '{{$item->item}}', {{intval($item->requested)}})" class="btn btn-primary"><i class="far fa-save"></i></button>
                                    @else
                                        <button wire:click="modifyPackage({{$item->id}}, '{{$item->item}}', {{intval($item->requested)}})" class="btn btn-primary"><i class="fas fa-pencil-alt"></i></button>
                                    @endif
                                    <a wire:click="confirm({{$item->id}})" class="btn btn-danger text-white cart-delete-item"><i class="far fa-trash-alt"></i></a>
                                </td>
                            </tr>

                            @if(array_key_exists($item->id, $show) and $show[$item->id] == 'show')
                                <tr class="opened-details">
                                    <td colspan="4">
                                        <table class="table table-default">
                                            <thead>
                                                <tr>
                                                    <th>{{__('Homepage')}}</th>
                                                    @if($option == 'blog content link')
                                                        <th>{{__('Section')}}</th>
                                                    @else
                                                        <th>{{__('Category')}}</th>
                                                    @endif
                                                    @if(!in_array($option, array('startpage article', 'blog article')))
                                                        <th>{{__('Anchor')}}</th>
                                                    @endif
                                                    @if(in_array($option, array('startpage article', 'blog article')))
                                                        <th>{{__('Article')}}</th>
                                                    @else
                                                        <th>{{__('Title')}}</th>
                                                    @endif
                                                    <th>{{__('URL')}}</th>
                                                    @if(!in_array($option, array('startpage article', 'blog article')))
                                                        <th>{{__('Follow')}}</th>
                                                        <th>{{__('New tab')}}</th>
                                                    @endif
                                                    <th>{{__('Start date')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            @if(!empty($edit) and $edit == $item->id)
                                                @if(!empty($json_authority))
                                                    @if($option == 'packages')
                                                        @foreach($json_authority as $i => $value)
                                                            <tr>
                                                                <td>
                                                                    {{ remove_http(@$sites[$i]['url']) }}
                                                                    <p class="text-ghost mb-0">{{ type_page($sites[$i]['type']) }}</p>
                                                                </td>
                                                                <td>
                                                                    @if(!empty($categories[$i]))
                                                                        <div class="form-group mb-0">
                                                                            <select class="form-control d-inline-block select-xs inputs mr-2 is_category" data-package="{{ $i }}" wire:ignore>
                                                                                <option value="">{{__('Choose category')}}</option>
                                                                                @foreach($categories[$i] as $category)
                                                                                    <option value="{{ $category['id'] }}" @if($category['id'] == $json_category[$i]) selected="selected" @endif>{{ $category['name'] }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    @else
                                                                        <span class="text-muted">-</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control inputs-small is_anchor" placeholder="{{__('Link anchor')}}" autocomplete="off" wire:model="json_anchor.{{ $i }}" />
                                                                    @error('json_anchor.'.$i) <span class="error">{{ $message }}</span> @enderror
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control inputs-small is_title" placeholder="{{__('Link title')}}" autocomplete="off" wire:model="json_title.{{ $i }}" />
                                                                    @error('json_title.'.$i) <span class="error">{{ $message }}</span> @enderror
                                                                </td>
                                                                <td>
                                                                    <input type="url" class="form-control inputs-small is_url" placeholder="{{__('Link url')}}" autocomplete="off" wire:model="json_url.{{ $i }}" />
                                                                    @error('json_url.'.$i) <span class="error">{{ $message }}</span> @enderror
                                                                </td>
                                                                <td>
                                                                    <input id="json_follow.{{ $i }}" wire:model="json_follow.{{ $i }}" type="checkbox" class="styled-checkbox">
                                                                    <label for="json_follow.{{ $i }}">&nbsp;</label>
                                                                    @error('json_follow.'.$i) <span class="error">{{ $message }}</span> @enderror
                                                                </td>
                                                                <td>
                                                                    <input id="json_blank.{{ $i }}" wire:model="json_blank.{{ $i }}" type="checkbox" class="styled-checkbox">
                                                                    <label for="json_blank.{{ $i }}">&nbsp;</label>
                                                                    @error('json_blank.'.$i) <span class="error">{{ $message }}</span> @enderror
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control inputs-small inputs-datepicker datepicker" placeholder="{{__('Select the date')}}" autocomplete="off" data-date-format="dd.mm.yyyy" data-package="{{ $i }}" value="{{ short_date($json_date[$i], '-') }}" wire:ignore />
                                                                    @error('json_date.'.$i) <span class="error">{{ $message }}</span> @enderror
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td class="align-middle">
                                                                {{ remove_http(@$sites[0]['url']) }}
                                                                <p class="text-ghost mb-0">{{ type_page($sites[0]['type']) }}</p>
                                                            </td>
                                                            <td class="align-middle">
                                                                @if($option == 'blog content link')
                                                                    @if(is_numeric($json_section[0]))
                                                                        <span class="text-muted">{{ (intval($json_section[0]) + 1) }}</span>
                                                                    @else
                                                                        <span class="text-muted">-</span>
                                                                    @endif
                                                                @else
                                                                    @if(intval($requested) == 1)
                                                                        <span class="text-muted">-</span>
                                                                    @elseif(!empty($categories[0]))
                                                                        <div class="form-group mb-0">
                                                                            <select class="form-control d-inline-block select-xs inputs mr-2 is_category" data-package="{{ $edit }}" wire:ignore>
                                                                                <option value="">{{__('Choose category')}}</option>
                                                                                @foreach($categories[0] as $category)
                                                                                    <option value="{{ $category['id'] }}" @if($category['id'] == $json_category[0]) selected="selected" @endif>{{ $category['name'] }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    @else
                                                                        <span class="text-muted">-</span>
                                                                    @endif
                                                                @endif
                                                            </td>
                                                            @if(!in_array($option, array('startpage article', 'blog article')))
                                                            <td class="align-middle">
                                                                <input type="text" class="form-control inputs-small is_anchor" placeholder="{{__('Link anchor')}}" autocomplete="off" wire:model="json_anchor.0" />
                                                                @error('json_anchor.0') <span class="error">{{ $message }}</span> @enderror
                                                            </td>
                                                            @endif
                                                            <td class="align-middle">
                                                                <input type="text" class="form-control inputs-small is_title" placeholder="{{__('Link title')}}" autocomplete="off" wire:model="json_title.0" />
                                                                @error('json_title.0') <span class="error">{{ $message }}</span> @enderror
                                                            </td>
                                                            <td class="align-middle">
                                                                <input type="url" class="form-control inputs-small is_url" placeholder="{{__('Link url')}}" autocomplete="off" wire:model="json_url.0" />
                                                                @error('json_url.0') <span class="error">{{ $message }}</span> @enderror
                                                            </td>
                                                            @if(!in_array($option, array('startpage article', 'blog article')))
                                                                <td class="align-middle">
                                                                    <input id="json_follow.0" wire:model="json_follow.0" type="checkbox" class="styled-checkbox">
                                                                    <label for="json_follow.0">&nbsp;</label>
                                                                    @error('json_follow.0') <span class="error">{{ $message }}</span> @enderror
                                                                </td>
                                                                <td class="align-middle">
                                                                    <input id="json_blank.0" wire:model="json_blank.0" type="checkbox" class="styled-checkbox">
                                                                    <label for="json_blank.0">&nbsp;</label>
                                                                    @error('json_blank.0') <span class="error">{{ $message }}</span> @enderror
                                                                </td>
                                                            @endif
                                                            <td>
                                                                @if($json_years[0] == -5)
                                                                    <p class="muted">Permanent</p>
                                                                @else
                                                                    <select wire:model="json_years.0" class="form-control custom-select mb-2">
                                                                        <option value="">{{__('Select the years')}}</option>
                                                                        <option value="1">{{__('1 year')}}</option>
                                                                        <option value="2">{{__('2 years')}}</option>
                                                                        <option value="3">{{__('3 years')}}</option>
                                                                        <option value="4">{{__('4 years')}}</option>
                                                                        <option value="5">{{__('5 years')}}</option>
                                                                    </select>
                                                                @endif
                                                                <input type="text" class="form-control inputs-small inputs-datepicker datepicker" placeholder="{{__('Select the date')}}" autocomplete="off" data-date-format="dd.mm.yyyy" data-package="{{ $edit }}" value="{{ short_date($json_date[0], '-') }}" wire:ignore />
                                                                @error('json_date.0') <span class="error">{{ $message }}</span> @enderror
                                                            </td>
                                                        </tr>
                                                    @endif

                                                @endif
                                            @else
                                                @if(!empty($details))
                                                    @if($option == 'packages')
                                                        @foreach($details as $value)
                                                            <tr>
                                                                <td>
                                                                    {{ remove_http($value['site']) }}
                                                                    <p class="text-ghost mb-0">{{ type_page($value['type']) }}</p>
                                                                </td>
                                                                <td>@if(!empty($value['category'])) {{ $value['category'] }} @else <span class="text-ghost">-</span> @endif</td>
                                                                <td>{{ $value['anchor'] }}</td>
                                                                <td>{{ $value['title'] }}</td>
                                                                <td>{{ $value['url'] }}</td>
                                                                <td class="text-center">@if($value['follow'] == 'follow') <i class="fas fa-check text-blue"></i> @else <i class="fas fa-times text-ghost"></i> @endif</td>
                                                                <td class="text-center">@if(intval($value['blank']) == 1) <i class="fas fa-check text-blue"></i> @else <i class="fas fa-times text-ghost"></i> @endif</td>
                                                                <td>
                                                                    {{ short_date($value['date']) }}
                                                                    <p class="text-ghost mb-0">{{__('1 year')}}</p>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td>
                                                                {{ remove_http($details['site']) }}
                                                                <p class="text-ghost mb-0">{{ type_page($details['type']) }}</p>
                                                            </td>
                                                            @if($option == 'blog content link' AND isset($details['section']))
                                                                <td>@if(is_numeric($details['section'])) {{ intval($details['section']) + 1 }} @else <span class="text-ghost">-</span> @endif</td>
                                                            @else
                                                                <td>@if(!empty($details['category'])) {{ $details['category'] }} @else <span class="text-ghost">-</span> @endif</td>
                                                            @endif
                                                            @if(!in_array($option, array('startpage article', 'blog article')))
                                                                <td>{{ @$details['anchor'] }}</td>
                                                            @endif
                                                            @if(in_array($option, array('startpage article', 'blog article')))
                                                                <td>
                                                                    <div class="row">
                                                                        @if(!empty($details['image']))
                                                                            <div class="col-2">
                                                                                <img src="{{ $details['image'] }}" />
                                                                            </div>
                                                                        @endif
                                                                        <div @if(!empty($details['image'])) class="col-10" @else class="col-12" @endif style="overflow-x: hidden">
                                                                            {{ $details['title'] }}
                                                                            <br>
                                                                            @if (isset($details['content']))
                                                                                <small class="text-ghost">{{ get_excerpt($details['content']) }}</small>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            @else
                                                                <td>{{ $details['title'] }}</td>
                                                            @endif
                                                            <td>{{ $details['url'] }}</td>
                                                            @if(!in_array($option, array('startpage article', 'blog article')))
                                                                <td class="text-center">@if(@$details['follow'] == 'follow') <i class="fas fa-check text-blue"></i> @else <i class="fas fa-times text-ghost"></i> @endif</td>
                                                                <td class="text-center">@if(intval(@$details['blank']) == 1) <i class="fas fa-check text-blue"></i> @else <i class="fas fa-times text-ghost"></i> @endif</td>
                                                            @endif
                                                            <td>
                                                                {{ short_date($details['date']) }}
                                                                <p class="text-ghost mb-0">{{ $details['years'] == - 5 ? 'Permanent' : $details['years'] }} {{ $details['years'] != - 5 ? plural_or_singular('year', $details['years']) : '' }}</p>
                                                            </td>
                                                        </tr>
                                                    @endif

                                                @endif
                                            @endif

                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endif

                        @endforeach
                        </tbody>
                    </table>
                </div>

                @if($pages > 1)
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            @for($i = 1; $i <= $pages; $i++)
                                @if($i == $page)
                                    <li class="page-item active">
                                        <span class="page-link">{{ $i }}</span>
                                    </li>
                                @else
                                    <li class="page-item"><a class="page-link" wire:click="pagination({{$i}})">{{ $i }}</a></li>
                                @endif
                            @endfor
                        </ul>
                    </nav>
                @endif

                <div class="row mt-1">
                    <div class="col-12 col-lg-auto text-center">
                        <a href="{{ route('customer_dashboard') }}" class="btn btn-link"><i class="fas fa-long-arrow-alt-left"></i> {{__('Continue shopping')}}</a>
                    </div>
                    <div class="col-12 col-lg-4 ml-auto text-right">
                        <div class="white-container">
                            <table width="100%">
                                <tr>
                                    <td class="text-right">{{__('Subtotal')}}</td>
                                    <td class="text-right">{{ currency() }} {{ get_price($subtotal) }}</td>
                                </tr>
                                @if(count($discount) > 0)
                                    @foreach($discount as $item)
                                        <tr>
                                            <td class="text-right">{{__($item['type'])}}: {{ $item['percentage'] }}</td>
                                            <td class="text-right">{{ currency() }} -{{ get_price($item['discount']) }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                <tr>
                                    <td class="text-right">{{__('Total')}}</td>
                                    <td class="text-right">{{ currency() }} {{ get_price($total) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right">{{__('Taxes')}} {{ integer_or_float($vat) }}%</td>
                                    <td class="text-right">{{ get_price($percent) }}</td>
                                </tr>
                                @if(floatval($coins) > 0)
                                    <tr>
                                        <td class="text-right @if(floatval($coins) > 0) text-muted @endif">{{__('Total due')}}</td>
                                        <td class="text-right">@if(floatval($coins) > 0)<s class="text-muted">@endif{{ currency() }} {{ get_price($discounted) }}@if(floatval($coins) > 0)</s>@endif</td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">{{__('My coins')}}</td>
                                        <td class="text-right">{{ currency() }} -{{ get_price($coins) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="pt-4">
                                            <p class="text-subtotal">{{__('Total due')}}</p>
                                            <p class="number-subtotal">{{ currency() }} {{ get_price($payment) }}</p>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="2" class="pt-4">
                                            <p class="text-subtotal">{{__('Total due')}}</p>
                                            <p class="number-subtotal">{{ currency() }} {{ get_price($payment) }}</p>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="2" class="text-center">
                                        {{-- <a href="javascript:void(0)" wire:click="doNotify" wire:loading.remove class="btn btn-primary btn-block mt-4 mb-2">{{__('Pay now')}}</a> --}}
                                        <a href="javascript:void(0)" wire:click="doNotify" wire:loading.remove class="btn btn-primary btn-block mt-4 mb-2"
                                        style="background: #ffc439; border: 1px solid #ffc439;">
                                            <img width="auto" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAxcHgiIGhlaWdodD0iMzIiIHZpZXdCb3g9IjAgMCAxMDEgMzIiIHByZXNlcnZlQXNwZWN0UmF0aW89InhNaW5ZTWluIG1lZXQiIHhtbG5zPSJodHRwOiYjeDJGOyYjeDJGO3d3dy53My5vcmcmI3gyRjsyMDAwJiN4MkY7c3ZnIj48cGF0aCBmaWxsPSIjMDAzMDg3IiBkPSJNIDEyLjIzNyAyLjggTCA0LjQzNyAyLjggQyAzLjkzNyAyLjggMy40MzcgMy4yIDMuMzM3IDMuNyBMIDAuMjM3IDIzLjcgQyAwLjEzNyAyNC4xIDAuNDM3IDI0LjQgMC44MzcgMjQuNCBMIDQuNTM3IDI0LjQgQyA1LjAzNyAyNC40IDUuNTM3IDI0IDUuNjM3IDIzLjUgTCA2LjQzNyAxOC4xIEMgNi41MzcgMTcuNiA2LjkzNyAxNy4yIDcuNTM3IDE3LjIgTCAxMC4wMzcgMTcuMiBDIDE1LjEzNyAxNy4yIDE4LjEzNyAxNC43IDE4LjkzNyA5LjggQyAxOS4yMzcgNy43IDE4LjkzNyA2IDE3LjkzNyA0LjggQyAxNi44MzcgMy41IDE0LjgzNyAyLjggMTIuMjM3IDIuOCBaIE0gMTMuMTM3IDEwLjEgQyAxMi43MzcgMTIuOSAxMC41MzcgMTIuOSA4LjUzNyAxMi45IEwgNy4zMzcgMTIuOSBMIDguMTM3IDcuNyBDIDguMTM3IDcuNCA4LjQzNyA3LjIgOC43MzcgNy4yIEwgOS4yMzcgNy4yIEMgMTAuNjM3IDcuMiAxMS45MzcgNy4yIDEyLjYzNyA4IEMgMTMuMTM3IDguNCAxMy4zMzcgOS4xIDEzLjEzNyAxMC4xIFoiPjwvcGF0aD48cGF0aCBmaWxsPSIjMDAzMDg3IiBkPSJNIDM1LjQzNyAxMCBMIDMxLjczNyAxMCBDIDMxLjQzNyAxMCAzMS4xMzcgMTAuMiAzMS4xMzcgMTAuNSBMIDMwLjkzNyAxMS41IEwgMzAuNjM3IDExLjEgQyAyOS44MzcgOS45IDI4LjAzNyA5LjUgMjYuMjM3IDkuNSBDIDIyLjEzNyA5LjUgMTguNjM3IDEyLjYgMTcuOTM3IDE3IEMgMTcuNTM3IDE5LjIgMTguMDM3IDIxLjMgMTkuMzM3IDIyLjcgQyAyMC40MzcgMjQgMjIuMTM3IDI0LjYgMjQuMDM3IDI0LjYgQyAyNy4zMzcgMjQuNiAyOS4yMzcgMjIuNSAyOS4yMzcgMjIuNSBMIDI5LjAzNyAyMy41IEMgMjguOTM3IDIzLjkgMjkuMjM3IDI0LjMgMjkuNjM3IDI0LjMgTCAzMy4wMzcgMjQuMyBDIDMzLjUzNyAyNC4zIDM0LjAzNyAyMy45IDM0LjEzNyAyMy40IEwgMzYuMTM3IDEwLjYgQyAzNi4yMzcgMTAuNCAzNS44MzcgMTAgMzUuNDM3IDEwIFogTSAzMC4zMzcgMTcuMiBDIDI5LjkzNyAxOS4zIDI4LjMzNyAyMC44IDI2LjEzNyAyMC44IEMgMjUuMDM3IDIwLjggMjQuMjM3IDIwLjUgMjMuNjM3IDE5LjggQyAyMy4wMzcgMTkuMSAyMi44MzcgMTguMiAyMy4wMzcgMTcuMiBDIDIzLjMzNyAxNS4xIDI1LjEzNyAxMy42IDI3LjIzNyAxMy42IEMgMjguMzM3IDEzLjYgMjkuMTM3IDE0IDI5LjczNyAxNC42IEMgMzAuMjM3IDE1LjMgMzAuNDM3IDE2LjIgMzAuMzM3IDE3LjIgWiI+PC9wYXRoPjxwYXRoIGZpbGw9IiMwMDMwODciIGQ9Ik0gNTUuMzM3IDEwIEwgNTEuNjM3IDEwIEMgNTEuMjM3IDEwIDUwLjkzNyAxMC4yIDUwLjczNyAxMC41IEwgNDUuNTM3IDE4LjEgTCA0My4zMzcgMTAuOCBDIDQzLjIzNyAxMC4zIDQyLjczNyAxMCA0Mi4zMzcgMTAgTCAzOC42MzcgMTAgQyAzOC4yMzcgMTAgMzcuODM3IDEwLjQgMzguMDM3IDEwLjkgTCA0Mi4xMzcgMjMgTCAzOC4yMzcgMjguNCBDIDM3LjkzNyAyOC44IDM4LjIzNyAyOS40IDM4LjczNyAyOS40IEwgNDIuNDM3IDI5LjQgQyA0Mi44MzcgMjkuNCA0My4xMzcgMjkuMiA0My4zMzcgMjguOSBMIDU1LjgzNyAxMC45IEMgNTYuMTM3IDEwLjYgNTUuODM3IDEwIDU1LjMzNyAxMCBaIj48L3BhdGg+PHBhdGggZmlsbD0iIzAwOWNkZSIgZD0iTSA2Ny43MzcgMi44IEwgNTkuOTM3IDIuOCBDIDU5LjQzNyAyLjggNTguOTM3IDMuMiA1OC44MzcgMy43IEwgNTUuNzM3IDIzLjYgQyA1NS42MzcgMjQgNTUuOTM3IDI0LjMgNTYuMzM3IDI0LjMgTCA2MC4zMzcgMjQuMyBDIDYwLjczNyAyNC4zIDYxLjAzNyAyNCA2MS4wMzcgMjMuNyBMIDYxLjkzNyAxOCBDIDYyLjAzNyAxNy41IDYyLjQzNyAxNy4xIDYzLjAzNyAxNy4xIEwgNjUuNTM3IDE3LjEgQyA3MC42MzcgMTcuMSA3My42MzcgMTQuNiA3NC40MzcgOS43IEMgNzQuNzM3IDcuNiA3NC40MzcgNS45IDczLjQzNyA0LjcgQyA3Mi4yMzcgMy41IDcwLjMzNyAyLjggNjcuNzM3IDIuOCBaIE0gNjguNjM3IDEwLjEgQyA2OC4yMzcgMTIuOSA2Ni4wMzcgMTIuOSA2NC4wMzcgMTIuOSBMIDYyLjgzNyAxMi45IEwgNjMuNjM3IDcuNyBDIDYzLjYzNyA3LjQgNjMuOTM3IDcuMiA2NC4yMzcgNy4yIEwgNjQuNzM3IDcuMiBDIDY2LjEzNyA3LjIgNjcuNDM3IDcuMiA2OC4xMzcgOCBDIDY4LjYzNyA4LjQgNjguNzM3IDkuMSA2OC42MzcgMTAuMSBaIj48L3BhdGg+PHBhdGggZmlsbD0iIzAwOWNkZSIgZD0iTSA5MC45MzcgMTAgTCA4Ny4yMzcgMTAgQyA4Ni45MzcgMTAgODYuNjM3IDEwLjIgODYuNjM3IDEwLjUgTCA4Ni40MzcgMTEuNSBMIDg2LjEzNyAxMS4xIEMgODUuMzM3IDkuOSA4My41MzcgOS41IDgxLjczNyA5LjUgQyA3Ny42MzcgOS41IDc0LjEzNyAxMi42IDczLjQzNyAxNyBDIDczLjAzNyAxOS4yIDczLjUzNyAyMS4zIDc0LjgzNyAyMi43IEMgNzUuOTM3IDI0IDc3LjYzNyAyNC42IDc5LjUzNyAyNC42IEMgODIuODM3IDI0LjYgODQuNzM3IDIyLjUgODQuNzM3IDIyLjUgTCA4NC41MzcgMjMuNSBDIDg0LjQzNyAyMy45IDg0LjczNyAyNC4zIDg1LjEzNyAyNC4zIEwgODguNTM3IDI0LjMgQyA4OS4wMzcgMjQuMyA4OS41MzcgMjMuOSA4OS42MzcgMjMuNCBMIDkxLjYzNyAxMC42IEMgOTEuNjM3IDEwLjQgOTEuMzM3IDEwIDkwLjkzNyAxMCBaIE0gODUuNzM3IDE3LjIgQyA4NS4zMzcgMTkuMyA4My43MzcgMjAuOCA4MS41MzcgMjAuOCBDIDgwLjQzNyAyMC44IDc5LjYzNyAyMC41IDc5LjAzNyAxOS44IEMgNzguNDM3IDE5LjEgNzguMjM3IDE4LjIgNzguNDM3IDE3LjIgQyA3OC43MzcgMTUuMSA4MC41MzcgMTMuNiA4Mi42MzcgMTMuNiBDIDgzLjczNyAxMy42IDg0LjUzNyAxNCA4NS4xMzcgMTQuNiBDIDg1LjczNyAxNS4zIDg1LjkzNyAxNi4yIDg1LjczNyAxNy4yIFoiPjwvcGF0aD48cGF0aCBmaWxsPSIjMDA5Y2RlIiBkPSJNIDk1LjMzNyAzLjMgTCA5Mi4xMzcgMjMuNiBDIDkyLjAzNyAyNCA5Mi4zMzcgMjQuMyA5Mi43MzcgMjQuMyBMIDk1LjkzNyAyNC4zIEMgOTYuNDM3IDI0LjMgOTYuOTM3IDIzLjkgOTcuMDM3IDIzLjQgTCAxMDAuMjM3IDMuNSBDIDEwMC4zMzcgMy4xIDEwMC4wMzcgMi44IDk5LjYzNyAyLjggTCA5Ni4wMzcgMi44IEMgOTUuNjM3IDIuOCA5NS40MzcgMyA5NS4zMzcgMy4zIFoiPjwvcGF0aD48L3N2Zz4" alt="">
                                        </a>
                                        <div class="inline-block w-100" wire:loading wire:target="doNotify">
                                            <a href="javascript:void(0)" class="btn btn-primary btn-loading btn-block mt-4 mb-2"><i class="fas fa-spinner fa-spin"></i> {{__('Please wait')}}...</a>
                                        </div>
                                        {{-- <small class="mollie"><i class="fas fa-lock"></i> {{__('All payments are made in a safe, protected environment by Mollie')}}™</small> --}}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="white-container text-center mt-5 pt-5 pb-5">
                    @if(!empty($search))
                        <p class="text-muted"><em>{{__('No results were found for your search')}}</em></p>
                    @else
                        <p class="text-muted"><em>{{__('Your shopping cart is empty')}}</em></p>
                    @endif
                </div>
            @endif
        </div>
    @endif
</div>

@push('scripts')
    <script>
        window.addEventListener('doAlert', event => {
            dialogAlert(event.detail.message, event.detail.cancel);
        });
        window.addEventListener('doRemove', event => {
            dialogRemove(event.detail.message, event.detail.confirm, event.detail.cancel);
        });
        window.addEventListener('doComplete', event => {
            dialogAlert(event.detail.message, event.detail.cancel);
        });
        window.addEventListener('triggerShow', event => {
            document.getElementById('show_details_' + event.detail.id).click();
        });
        window.addEventListener('triggerHide', event => {
            document.getElementById('hide_details_' + event.detail.id).click();
        });
        window.addEventListener('triggerRedirect', event => {
            setTimeout(function(){
                window.location.href = event.detail.url;
            }, 1500);
        });
        window.addEventListener('countCategories', event => {
            @this.set('max_fields', $('.is_title').length);
            @this.set('max_categories', $('.is_category').length);
        });
        window.addEventListener('loadDatepicker', event => {
            $('.datepicker').datepicker('destroy')
            $('.datepicker').datepicker({
                autoHide: true,
                format: 'dd-mm-yyyy',
                startDate: Date.now()
            });
        });
        $(document).on('change', '.is_category', function() {
            @this.categoryUpdated($(this).data('package'), $(this).val());
        });
        $(document).on('change', '.datepicker', function() {
            @this.dateUpdated($(this).data('package'), $(this).val());
        });
        window.addEventListener('onCart', event => {
            $('.cart-details').addClass('animated fadeIn').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function (){ $(this).removeClass('animated fadeIn'); });
        });
        window.addEventListener('onNotify', event => {
            $('.cart-notification').addClass('animated fadeIn').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function (){ $(this).removeClass('animated fadeIn'); });
        });
        window.addEventListener('onResend', event => {
            $('.re-sent-message small').width($('.verification_text').width());
            $('.re-sent-message').show().delay(3000).fadeOut('slow');
        });
        window.addEventListener('onVerify', event => {
            $('.verification-container').addClass('animated fadeIn').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function (){ $(this).removeClass('animated fadeIn'); });

            const codes = document.querySelectorAll(".code");
            codes[0].focus();
            codes.forEach((code, idx) => {
                code.addEventListener("keydown", (e) => {
                    if (e.key >= 0 && e.key <= 9) {
                        codes[idx].value = "";
                        if(codes[idx + 1] !== undefined) {
                            setTimeout(() => codes[idx + 1].focus(), 10);
                        }
                    } else if (e.key === "Backspace") {
                        if(codes[idx - 1] !== undefined) {
                            setTimeout(() => codes[idx - 1].focus(), 10);
                        }
                    }
                });
            });
        });
        function doRemove() {
            @this.remove();
        }
    </script>
@endpush
