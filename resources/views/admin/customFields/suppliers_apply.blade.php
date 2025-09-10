<x-site-layout :internal=true>
       <!-- start:: section -->
        <section class="section">
          <div class="container">
            <div class="row">
              <div class="col-lg-9 mx-auto">
                <div class="section-content border-0 card-gray p-4 p-lg-5">
                  <div class="row">
                    <div class="col-12">
                      <div class="border-bottom pb-4 mb-3">
                        <h2 class="font-bold text-main mb-3">قدّم طلبك الآن</h2>
                        <h3 class="text-main">املأ النموذج أدناه ليتم مراجعة طلبك والانضمام إلى فريقنا كمورد.</h3>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12">
                      <form action="{{route('suppliers.apply')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                              <label class="mb-2">الاسم الكامل للمنظمة ( كما هو في السجل التجاري)</label>
                              <input class="form-control" type="text" name="name" value="{{old('name')}}" placeholder="أدخل الاسم الكامل للمنظمة ( كما هو في السجل التجاري)" />
                              @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                               @endif
                            </div>
                          </div>
                          <div class="col-12">
                            <div class="form-group">
                              <label class="mb-2">رقم السجل التجاري</label>
                              <input class="form-control" type="text" placeholder="000 000 000 000 000 000 000" name="commercial_recored" value="{{ old('commercial_recored')}}" />
                              @if ($errors->has('commercial_recored'))
                                    <span class="text-danger">{{ $errors->first('commercial_recored') }}</span>
                               @endif
                            </div>
                          </div>
                          <div class="col-lg-6">
                            <div class="form-group">
                              <label class="mb-2">تاريخ الاصدار</label>
                              <div class="input-icon icon-left">
                                <input class="form-control datetimepicker" type="text" placeholder="تاريخ الاصدار" id="datetimepicker" name="issue_date" value="{{old('issue_date')}}" />
                                <div class="icon"><img src="{{asset('assets/images/calendar2.svg')}}" alt="" /></div>
                                @if ($errors->has('issue_date'))
                                    <span class="text-danger">{{ $errors->first('issue_date') }}</span>
                               @endif
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-6">
                            <div class="form-group">
                              <label class="mb-2">تاريخ الانتهاء</label>
                              <div class="input-icon icon-left">
                                <input class="form-control datetimepicker" type="text" placeholder="تاريخ الانتهاء" id="datetimepicker" name="expire_date" value="{{old('expire_date')}}" />
                                <div class="icon"><img src="{{asset('assets/images/calendar2.svg')}}" alt="" /></div>
                                 @if ($errors->has('expire_date'))
                                    <span class="text-danger">{{ $errors->first('expire_date') }}</span>
                               @endif
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="mb-2"> شركة تابعة لـ</label>
                              <select class="selectpicker form-control" title="اختر" data-live-search="true" name="related_to"  >
                                @foreach ($countries as $key => $value)
                                    <option value="{{ $key }}" @if(old('related_to') == $key) {{'selected'}} @endif>{{ $value }} </option>
                                @endforeach
                              </select>
                               @if ($errors->has('related_to'))
                                    <span class="text-danger">{{ $errors->first('related_to') }}</span>
                               @endif
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="mb-2"> صادر من</label>
                              <select class="selectpicker form-control" title="اختر" data-live-search="true" name="issue_from">
                                @foreach ($cities as $key => $value)
                                    <option value="{{ $key }}" @if(old('issue_from') == $key) {{'selected'}} @endif>{{ $value }} </option>
                                @endforeach
                              </select>
                               @if ($errors->has('issue_from'))
                                    <span class="text-danger">{{ $errors->first('issue_from') }}</span>
                               @endif
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="mb-2">رقم الفاكس</label>
                              <input class="form-control" type="text" placeholder="000" name="fax_number" value="{{old('fax_number')}}"/>
                               @if ($errors->has('fax_number'))
                                    <span class="text-danger">{{ $errors->first('fax_number') }}</span>
                               @endif
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="mb-2">رقم الهاتف</label>
                              <div class="row flex-nowrap gx-2">
                                <div class="col">
                                  <input class="form-control text-end" type="text" placeholder="00" name="phone_number" value="{{old('phone_number')}}" />
                                   @if ($errors->has('phone_number'))
                                    <span class="text-danger">{{ $errors->first('phone_number') }}</span>
                                  @endif
                                </div>
                                {{-- <div class="col-auto">
                                  <select class="form-control selectpicker" data-live-search="true">
                                    <option data-content='+966 &lt;img src="https://flagcdn.com/w40/sa.png" class="flag-icon"&gt; ' value="+966">السعودية</option>
                                    <option data-content='+977 &lt;img src="https://flagcdn.com/w40/eg.png" class="flag-icon"&gt; ' value="+977">مصر </option>
                                    <option data-content='+971 &lt;img src="https://flagcdn.com/w40/ae.png" class="flag-icon"&gt; ' value="+971">الإمارات </option>
                                    <option data-content='+926 &lt;img src="https://flagcdn.com/w40/jo.png" class="flag-icon"&gt; ' value="+926">الأردن </option>
                                  </select>
                                </div> --}}
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="mb-2"> الدولة</label>
                              <select class="selectpicker form-control" title="اختر" data-live-search="true"  name="country">
                                 @foreach ($countries as $key => $value)
                                    <option value="{{ $key }}" @if(old('country') == $key) {{'selected'}} @endif>{{ $value }} </option>
                                @endforeach
                              </select>
                              @if ($errors->has('country'))
                                    <span class="text-danger">{{ $errors->first('country') }}</span>
                                  @endif
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="mb-2"> المدينة</label>
                              <select class="selectpicker form-control" title="اختر" data-live-search="true"  name="city">
                               @foreach ($cities as $key => $value)
                                    <option value="{{ $key }}" @if(old('city') == $key) {{'selected'}} @endif>{{ $value }} </option>
                                @endforeach
                              </select>
                              @if ($errors->has('city'))
                                <span class="text-danger">{{ $errors->first('city') }}</span>
                              @endif
                            </div>
                          </div>



                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="mb-2"> الشارع</label>
                              <input class="form-control" type="text" placeholder="أدخل الشارع" name="street" value="{{old('street')}}"/>
                              @if ($errors->has('street'))
                                <span class="text-danger">{{ $errors->first('street') }}</span>
                              @endif
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="mb-2"> الموقع الالكتروني</label>
                              <input class="form-control text-end" type="text" placeholder="URL /Website" name="website" value="{{old('website')}}"/>
                              @if ($errors->has('website'))
                                <span class="text-danger">{{ $errors->first('website') }}</span>
                              @endif
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                              <label class="mb-2">اسم المدير العام </label>
                              <input class="form-control" type="text" placeholder="أدخل اسم المدير العام" name="manager_name" value="{{old('manager_name')}}" />
                              @if ($errors->has('manager_name'))
                                <span class="text-danger">{{ $errors->first('manager_name') }}</span>
                              @endif
                            </div>
                          </div>
                        </div>
                         <hr class="mb-4" />
                          <h3 class="font-bold text-main mb-3">المعلومات والتخصصات </h3>
                          <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="mb-2"> المجال</label>
                              <select class="selectpicker form-control" title="اختر" data-live-search="true"  name="field_id" id="field_id">
                                 @foreach ($supplier_fields as $supplier_field)
                                    <option value="{{ $supplier_field->id }}" @if(old('field_id') == $key) {{'selected'}} @endif>{{ $supplier_field->name }} </option>
                                @endforeach
                              </select>
                              @if ($errors->has('field_id'))
                                    <span class="text-danger">{{ $errors->first('field_id') }}</span>
                                  @endif
                            </div>
                          </div>


                          <div class="col-md-6">
                            <div class="form-group">
                                <label class="mb-2">التخصص الرئيسي</label>
                                <select class="selectpicker form-control" title="اختر" data-live-search="true" id="main_major_id" name="main_major_id" autocomplete="off">
                                </select>
                                @if ($errors->has('main_major_id'))
                                    <span class="text-danger">{{ $errors->first('main_major_id') }}</span>
                                @endif
                            </div>
                        </div>


                          <div class="col-md-6">
                            <div class="form-group">
                                <label class="mb-2">التخصص الفرعي</label>
                                <select class="selectpicker form-control" title="اختر" data-live-search="true" id="sub_major_id" name="sub_major_id" autocomplete="off">
                                </select>
                                @if ($errors->has('sub_major_id'))
                                    <span class="text-danger">{{ $errors->first('sub_major_id') }}</span>
                                @endif
                            </div>
                        </div>


                         <div class="col-md-6">
                            <div class="form-group">
                              <label class="mb-2"> عدد الفروع</label>
                              <input class="form-control" type="text" placeholder="أدخل عدد الفروع" name="branches_count" value="{{old('branches_count')}}"/>
                              @if ($errors->has('branches_count'))
                                <span class="text-danger">{{ $errors->first('branches_count') }}</span>
                              @endif
                            </div>
                          </div>

                           <div class="col-md-6">
                            <div class="form-group">
                              <label class="mb-2"> عدد المشاريع القائمة</label>
                              <input class="form-control" type="text" placeholder="أدخل عدد المشاريع القائمة" name="active_projects_count" value="{{old('active_projects_count')}}"/>
                              @if ($errors->has('active_projects_count'))
                                <span class="text-danger">{{ $errors->first('active_projects_count') }}</span>
                              @endif
                            </div>
                          </div>



                           <div class="col-md-6">
                            <div class="form-group">
                              <label class="mb-2"> قيمة المشاريع القائمة</label>
                              <input class="form-control" type="text" placeholder="أدخل قيمة المشاريع القائمة" name="active_projects_amount" value="{{old('active_projects_amount')}}"/>
                              @if ($errors->has('active_projects_amount'))
                                <span class="text-danger">{{ $errors->first('active_projects_amount') }}</span>
                              @endif
                            </div>
                          </div>


                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="mb-2"> عدد المشاريع المكتملة</label>
                              <input class="form-control" type="text" placeholder="أدخل عدد المشاريع المكتملة" name="completed_projects_count" value="{{old('completed_projects_count')}}"/>
                              @if ($errors->has('completed_projects_count'))
                                <span class="text-danger">{{ $errors->first('completed_projects_count') }}</span>
                              @endif
                            </div>
                          </div>


                           <div class="col-md-6">
                            <div class="form-group">
                              <label class="mb-2"> قيمة المشاريع المكتملة</label>
                              <input class="form-control" type="text" placeholder="أدخل قيمة المشاريع المكتملة" name="completed_projects_amount" value="{{old('completed_projects_amount')}}"/>
                              @if ($errors->has('completed_projects_amount'))
                                <span class="text-danger">{{ $errors->first('completed_projects_amount') }}</span>
                              @endif
                            </div>
                          </div>


                          <div class="col-md-12">
                            <div class="form-group">
                              <label class="mb-2"> عدد المعدات</label>
                              <input class="form-control" type="text" placeholder="أدخل عدد المعدات" name="equipments_count" value="{{old('equipments_count')}}"/>
                              @if ($errors->has('equipments_count'))
                                <span class="text-danger">{{ $errors->first('equipments_count') }}</span>
                              @endif
                            </div>
                          </div>



                          <div class="col-md-12">
                            <div class="form-group">
                              <label class="mb-2"> قائمة المعدات</label>
                              <textarea name="equipments_list" rows="6" class="form-control">{{old('equipments_list')}}</textarea>
                              @if ($errors->has('equipments_list'))
                                <span class="text-danger">{{ $errors->first('equipments_list') }}</span>
                              @endif
                            </div>
                          </div>

                        <hr class="mb-4" />
                          <h3 class="font-bold text-main mb-3">المستندات الرسمية </h3>
                          <div class="row">
                           <div class="col-md-6">
                            <div class="form-group">
                              <label class="mb-2"> بروفايل الشركة</label>
                              <input type="file" class="form-control" name="company_profile" />
                              @if ($errors->has('company_profile'))
                                <span class="text-danger">{{ $errors->first('company_profile') }}</span>
                              @endif
                            </div>
                          </div>


                             <div class="col-md-6">
                            <div class="form-group">
                              <label class="mb-2"> السجل التجاري</label>
                              <input type="file" class="form-control" name="commercial_record" />
                              @if ($errors->has('commercial_record'))
                                <span class="text-danger">{{ $errors->first('commercial_record') }}</span>
                              @endif
                            </div>
                          </div>
                             <div class="col-md-6">
                            <div class="form-group">
                              <label class="mb-2"> شهادة تصنيف المقاولين</label>
                              <input type="file" class="form-control" name="contractor_classification_certificate" />
                              @if ($errors->has('contractor_classification_certificate'))
                                <span class="text-danger">{{ $errors->first('contractor_classification_certificate') }}</span>
                              @endif
                            </div>
                          </div>


                             <div class="col-md-6">
                            <div class="form-group">
                              <label class="mb-2"> شهادة هيئة السعودية</label>
                              <input type="file" class="form-control" name="saudi_authority_certificate" />
                              @if ($errors->has('saudi_authority_certificate'))
                                <span class="text-danger">{{ $errors->first('saudi_authority_certificate') }}</span>
                              @endif
                            </div>
                          </div>
                             <div class="col-md-6">
                            <div class="form-group">
                              <label class="mb-2"> شهادة التامينات الاجتماعية</label>
                              <input type="file" class="form-control" name="social_security_certificate" />
                              @if ($errors->has('social_security_certificate'))
                                <span class="text-danger">{{ $errors->first('social_security_certificate') }}</span>
                              @endif
                            </div>
                          </div>


                             <div class="col-md-6">
                            <div class="form-group">
                              <label class="mb-2"> شهادة مصلحة الزكاة والدخل</label>
                              <input type="file" class="form-control" name="zakat_income_tax_authority_certificate" />
                              @if ($errors->has('zakat_income_tax_authority_certificate'))
                                <span class="text-danger">{{ $errors->first('zakat_income_tax_authority_certificate') }}</span>
                              @endif
                            </div>
                          </div>
                             <div class="col-md-6">
                            <div class="form-group">
                              <label class="mb-2"> شهادة ضريبة القيمة المضافة</label>
                              <input type="file" class="form-control" name="vat_certificate" />
                              @if ($errors->has('vat_certificate'))
                                <span class="text-danger">{{ $errors->first('vat_certificate') }}</span>
                              @endif
                            </div>
                          </div>


                             <div class="col-md-6">
                            <div class="form-group">
                              <label class="mb-2"> شهادة المحتوى المحلي</label>
                              <input type="file" class="form-control" name="local_content_certificate" />
                              @if ($errors->has('local_content_certificate'))
                                <span class="text-danger">{{ $errors->first('local_content_certificate') }}</span>
                              @endif
                            </div>
                          </div>
                                <div class="col-md-12">
                            <div class="form-group">
                              <label class="mb-2"> شهادة الانجاز</label>
                              <input type="file" class="form-control" name="certificate_of_achievement" />
                              @if ($errors->has('certificate_of_achievement'))
                                <span class="text-danger">{{ $errors->first('certificate_of_achievement') }}</span>
                              @endif
                            </div>
                          </div>
                        </div>


                        <hr class="mb-4" />
                        <h3 class="font-bold text-main mb-3">معلومات شخص أخرى يتم التواصل معه</h3>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="mb-2">الاسم</label>
                              <input class="form-control" type="text" placeholder="أدخل الاسم" name="contact_name" value="{{old('contact_name')}}"/>
                              @if ($errors->has('contact_name'))
                                <span class="text-danger">{{ $errors->first('contact_name') }}</span>
                              @endif
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="mb-2">المسمى الوظيفي</label>
                              <input class="form-control" type="text" placeholder="أدخل المسمى الوظيفي" name="contact_position" value="{{old('contact_position')}}"/>
                              @if ($errors->has('contact_position'))
                                <span class="text-danger">{{ $errors->first('contact_position') }}</span>
                              @endif
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="mb-2">البريد الاكتروني</label>
                              <input class="form-control text-end" type="text" placeholder="test@test.com" name="contact_email" value="{{old('contact_email')}}" />
                              @if ($errors->has('contact_email'))
                                <span class="text-danger">{{ $errors->first('contact_email') }}</span>
                              @endif
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="mb-2">رقم الهاتف</label>
                              <div class="row flex-nowrap gx-2">
                                <div class="col">
                                  <input class="form-control text-end" type="text" placeholder="00"  name="contact_phone" value="{{old('contact_phone')}}"/>
                                  @if ($errors->has('contact_phone'))
                                    <span class="text-danger">{{ $errors->first('contact_phone') }}</span>
                                  @endif
                                </div>
                                {{-- <div class="col-auto">
                                  <select class="form-control selectpicker" data-live-search="true">
                                    <option data-content='+966 &lt;img src="https://flagcdn.com/w40/sa.png" class="flag-icon"&gt; ' value="+966">السعودية</option>
                                    <option data-content='+977 &lt;img src="https://flagcdn.com/w40/eg.png" class="flag-icon"&gt; ' value="+977">مصر </option>
                                    <option data-content='+971 &lt;img src="https://flagcdn.com/w40/ae.png" class="flag-icon"&gt; ' value="+971">الإمارات </option>
                                    <option data-content='+926 &lt;img src="https://flagcdn.com/w40/jo.png" class="flag-icon"&gt; ' value="+926">الأردن </option>
                                  </select>
                                </div> --}}
                              </div>
                            </div>
                          </div>
                          <div class="col-12">
                            <div class="form-group">
                              <label class="mb-2">رقم المكتب </label>
                              <input class="form-control" type="text" placeholder="000" name="contact_office_number" value="{{old('contact_office_number')}}"/>
                              @if ($errors->has('contact_office_number'))
                                <span class="text-danger">{{ $errors->first('contact_office_number') }}</span>
                              @endif
                            </div>
                          </div>
                          <div class="col-12">
                            <div class="form-group mb-0">
                              <button class="btn btn-white w-100" type="submit">تقديم طلب الانضام</button>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <!-- end:: section -->

        @section('scripts')
        <script>
            $(document).ready(function() {
                $('#field_id').on('change', function() {
                    var main_major_id = $(this).val();

                    $('#main_major_id').empty().val('').selectpicker('render').selectpicker('refresh');

                        $.ajax({
                            url : "{{ route('suppliers.get_main_majors', ':id') }}".replace(':id', encodeURIComponent(main_major_id)),
                            type: "GET",
                            dataType: "json",
                            success: function(data) {
                                $.each(data, function(key, value) {
                                    $('#main_major_id').append('<option value="' + key + '">' + value + '</option>');
                                });
                                $('#main_major_id').selectpicker('refresh').selectpicker('render');
                            }
                        });

                });


                 $('#main_major_id').on('change', function() {
                    var sub_major_id = $(this).val();

                    $('#sub_major_id').empty().val('').selectpicker('render').selectpicker('refresh');

                        $.ajax({
                            url : "{{ route('suppliers.get_sub_majors', ':id') }}".replace(':id', encodeURIComponent(sub_major_id)),
                            type: "GET",
                            dataType: "json",
                            success: function(data) {
                                $.each(data, function(key, value) {
                                    $('#sub_major_id').append('<option value="' + key + '">' + value + '</option>');
                                });
                                $('#sub_major_id').selectpicker('refresh').selectpicker('render');
                            }
                        });

                });

            });
        </script>
        @endsection
</x-site-layout>




<style>
    .main-header,
    .main-footer{
        display: none;
    }
    body{
        padding: 0 !important;
    }

</style>
<x-site-layout :internal=true>
        <!-- start:: section -->
    <section class="section section-login">
        <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="text-center mb-5">
                    {{-- <a class="navbar-brand  me-4" href="{{ route('main') }}"><img loading="lazy" class="filter-black" src="{{asset('assets/images/logo.png')}}" alt="" /></a> --}}
                </div>
            <div class="section-content p-4 p-lg-5">
                <div class="row">
                <div class="col-12">
                    <div class="border-bottom pb-4 mb-3">
                    <h2 class="font-bold text-main mb-3">انشاء تذكرة</h2>
                    <h3 class="text-main">فريق زمام القوة يرحب بكم.</h3>
                    </div>
                </div>
                </div>
                <div class="row">
                <div class="col-12">
                      <form method="post" action="{{ route('ticket.store') }}" class="create-form mb-3"
                                enctype="multipart/form-data" id="form-data">
                                @csrf

                                <input type="hidden" name="status" value="New Ticket" />
                                        <input type="hidden" name="type" value="Ticket" />
                    <div class="row">

                        <div class="col-12">
                        <div class="form-group">
                            <label class="mb-2">اسم الموظف</label>
                            <input class="form-control text-end" type="text" name="email" value="{{ old('email')}} " placeholder="test@test.com" />
                            @if ($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                        </div>
                        </div>
                          <div class="col-12">
                        <div class="form-group">
                            <label class="mb-2">الموضوع</label>
                            <input class="form-control text-end" type="text" name="subject" placeholder="{{ __($customField->placeholder) }}"
                            required="" value="{{ old('subject') }}" />
                            @if ($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                        </div>
                        </div>

                          <div class="col-6">
                        <div class="form-group">
                            <label class="mb-2">نوع الخدمة</label>
                           <select class="form-select" id="category" name="category" required
                        data-placeholder="{{ __($customField->placeholder) }}">
                        <option value="">{{ __($customField->placeholder) }}</option>
                        @foreach ($categoryTree as $category)
                            <option value="{{ $category['id'] }}" @if (old('category') == $category['id']) selected @endif>
                                {!! $category['name'] !!}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback d-block">
                        {{ $errors->first('category') }}
                    </div>
                        </div>
                        </div>

                          <div class="col-6">
                        <div class="form-group">
                            <label class="mb-2">الاولوية</label>
                         <select class="form-select" id="priority" name="priority" required
                        data-placeholder="{{ __($customField->placeholder) }}">
                        <option value="">{{ __($customField->placeholder) }}</option>
                        @foreach ($priorities as $priority)
                            <option value="{{ $priority->id }}" @if (old('priority') == $priority->id) selected @endif>
                                {{ $priority->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback d-block">
                        {{ $errors->first('priority') }}
                    </div>
                        </div>
                        </div>

                        <div class="col-12">
                        <div class="form-group">
                            <label class="mb-2">الوصف </label>
                           <textarea name="description"
                        class="form-control summernote-simple {{ $errors->has('description') ? 'is-invalid' : '' }}"
                        placeholder="{{ __($customField->placeholder) }}" required="">{{ old('description') }}</textarea>
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                    <p class="text-danger summernote_text"></p>
                        </div>
                        </div>

                          <div class="col-12">
                        <div class="form-group">
                            <label class="mb-2">المرفق</label>
                            <input type="file"
                                            class="form-control {{ $errors->has('attachments.') ? 'is-invalid' : '' }}"
                                            multiple="" name="attachments[]" id="chooseFile"
                                            data-filename="multiple_file_selection"
                                            {{ $customField->is_required == 1 ? 'required' : '' }}>
                        </div>
                        </div>

                        <div class="col-12">
                        @if ($errors->has('credentials'))
                                <div class="form-group mb-0">
                                <p class="text-danger text-center">{{ $errors->first('credentials') }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-12">
                        <div class="form-group mb-0">
                            <button class="btn btn-white w-100" type="submit">رفع التذكرة</button>
                        </div>
                        </div>
                    </div>
                    </form>
                </div>
                </div>
            </div>
            </div>
        </div>
        </div>
    </section>
    <!-- end:: section -->
</x-site-layout>
