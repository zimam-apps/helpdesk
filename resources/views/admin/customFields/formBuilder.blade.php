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
                            <input class="form-control text-end" type="text" name="subject" placeholder=" "
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
                        data-placeholder=" ">
                        <option value=""> </option>
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
                        data-placeholder=" ">
                        <option value=""> </option>
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
                        placeholder=" " required="">{{ old('description') }}</textarea>
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
                                           required>
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
