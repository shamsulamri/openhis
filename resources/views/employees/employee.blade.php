

    <div class='form-group  @if ($errors->has('title')) has-error @endif'>
        {{ Form::label('title', 'title',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('title', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('nickname')) has-error @endif'>
        {{ Form::label('nickname', 'nickname',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('nickname', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('nickname')) <p class="help-block">{{ $errors->first('nickname') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('app_date')) has-error @endif'>
        {{ Form::label('app_date', 'app_date',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('app_date', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'12']) }}
            @if ($errors->has('app_date')) <p class="help-block">{{ $errors->first('app_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('resign_date')) has-error @endif'>
        {{ Form::label('resign_date', 'resign_date',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('resign_date', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'12']) }}
            @if ($errors->has('resign_date')) <p class="help-block">{{ $errors->first('resign_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('conf_date')) has-error @endif'>
        {{ Form::label('conf_date', 'conf_date',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('conf_date', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'12']) }}
            @if ($errors->has('conf_date')) <p class="help-block">{{ $errors->first('conf_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('mobile')) has-error @endif'>
        {{ Form::label('mobile', 'mobile',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('mobile', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('mobile')) <p class="help-block">{{ $errors->first('mobile') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('office')) has-error @endif'>
        {{ Form::label('office', 'office',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('office', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('office')) <p class="help-block">{{ $errors->first('office') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('house')) has-error @endif'>
        {{ Form::label('house', 'house',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('house', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('house')) <p class="help-block">{{ $errors->first('house') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('code_campus')) has-error @endif'>
        {{ Form::label('code_campus', 'code_campus',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('code_campus', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('code_campus')) <p class="help-block">{{ $errors->first('code_campus') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('emp_type')) has-error @endif'>
        {{ Form::label('emp_type', 'emp_type',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('emp_type', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('emp_type')) <p class="help-block">{{ $errors->first('emp_type') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('emp_category')) has-error @endif'>
        {{ Form::label('emp_category', 'emp_category',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('emp_category', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'10']) }}
            @if ($errors->has('emp_category')) <p class="help-block">{{ $errors->first('emp_category') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('dept_id')) has-error @endif'>
        {{ Form::label('dept_id', 'dept_id',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('dept_id', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('dept_id')) <p class="help-block">{{ $errors->first('dept_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('name')) has-error @endif'>
        {{ Form::label('name', 'name',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('ic_passport')) has-error @endif'>
        {{ Form::label('ic_passport', 'ic_passport',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('ic_passport', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'14']) }}
            @if ($errors->has('ic_passport')) <p class="help-block">{{ $errors->first('ic_passport') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('passport')) has-error @endif'>
        {{ Form::label('passport', 'passport',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('passport', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('passport')) <p class="help-block">{{ $errors->first('passport') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('passport_sdate')) has-error @endif'>
        {{ Form::label('passport_sdate', 'passport_sdate',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('passport_sdate', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('passport_sdate')) <p class="help-block">{{ $errors->first('passport_sdate') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('passport_edate')) has-error @endif'>
        {{ Form::label('passport_edate', 'passport_edate',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('passport_edate', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('passport_edate')) <p class="help-block">{{ $errors->first('passport_edate') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('visa_no')) has-error @endif'>
        {{ Form::label('visa_no', 'visa_no',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('visa_no', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('visa_no')) <p class="help-block">{{ $errors->first('visa_no') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('visa_sdate')) has-error @endif'>
        {{ Form::label('visa_sdate', 'visa_sdate',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('visa_sdate', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('visa_sdate')) <p class="help-block">{{ $errors->first('visa_sdate') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('visa_edate')) has-error @endif'>
        {{ Form::label('visa_edate', 'visa_edate',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('visa_edate', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('visa_edate')) <p class="help-block">{{ $errors->first('visa_edate') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('expected_date')) has-error @endif'>
        {{ Form::label('expected_date', 'expected_date',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('expected_date', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'14']) }}
            @if ($errors->has('expected_date')) <p class="help-block">{{ $errors->first('expected_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('teach_no')) has-error @endif'>
        {{ Form::label('teach_no', 'teach_no',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('teach_no', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('teach_no')) <p class="help-block">{{ $errors->first('teach_no') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('teach_sdate')) has-error @endif'>
        {{ Form::label('teach_sdate', 'teach_sdate',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('teach_sdate', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('teach_sdate')) <p class="help-block">{{ $errors->first('teach_sdate') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('teach_edate')) has-error @endif'>
        {{ Form::label('teach_edate', 'teach_edate',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('teach_edate', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('teach_edate')) <p class="help-block">{{ $errors->first('teach_edate') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('emp_medical_status')) has-error @endif'>
        {{ Form::label('emp_medical_status', 'emp_medical_status',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('emp_medical_status', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'2']) }}
            @if ($errors->has('emp_medical_status')) <p class="help-block">{{ $errors->first('emp_medical_status') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('emp_medical_stat_date')) has-error @endif'>
        {{ Form::label('emp_medical_stat_date', 'emp_medical_stat_date',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('emp_medical_stat_date', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('emp_medical_stat_date')) <p class="help-block">{{ $errors->first('emp_medical_stat_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('kwsp_no')) has-error @endif'>
        {{ Form::label('kwsp_no', 'kwsp_no',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('kwsp_no', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('kwsp_no')) <p class="help-block">{{ $errors->first('kwsp_no') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('tax_no')) has-error @endif'>
        {{ Form::label('tax_no', 'tax_no',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('tax_no', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('tax_no')) <p class="help-block">{{ $errors->first('tax_no') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('employer_no')) has-error @endif'>
        {{ Form::label('employer_no', 'employer_no',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('employer_no', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('employer_no')) <p class="help-block">{{ $errors->first('employer_no') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('socso_no')) has-error @endif'>
        {{ Form::label('socso_no', 'socso_no',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('socso_no', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('socso_no')) <p class="help-block">{{ $errors->first('socso_no') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('zakat_no')) has-error @endif'>
        {{ Form::label('zakat_no', 'zakat_no',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('zakat_no', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('zakat_no')) <p class="help-block">{{ $errors->first('zakat_no') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('bumi_status')) has-error @endif'>
        {{ Form::label('bumi_status', 'bumi_status',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('bumi_status', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'10']) }}
            @if ($errors->has('bumi_status')) <p class="help-block">{{ $errors->first('bumi_status') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('ptptn_no')) has-error @endif'>
        {{ Form::label('ptptn_no', 'ptptn_no',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('ptptn_no', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('ptptn_no')) <p class="help-block">{{ $errors->first('ptptn_no') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('tabung_haji')) has-error @endif'>
        {{ Form::label('tabung_haji', 'tabung_haji',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('tabung_haji', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('tabung_haji')) <p class="help-block">{{ $errors->first('tabung_haji') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('unit_id')) has-error @endif'>
        {{ Form::label('unit_id', 'unit_id',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('unit_id', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('unit_id')) <p class="help-block">{{ $errors->first('unit_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('sub_unit_id')) has-error @endif'>
        {{ Form::label('sub_unit_id', 'sub_unit_id',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('sub_unit_id', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'10']) }}
            @if ($errors->has('sub_unit_id')) <p class="help-block">{{ $errors->first('sub_unit_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('old_ic')) has-error @endif'>
        {{ Form::label('old_ic', 'old_ic',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('old_ic', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('old_ic')) <p class="help-block">{{ $errors->first('old_ic') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('position')) has-error @endif'>
        {{ Form::label('position', 'position',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('position', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('position')) <p class="help-block">{{ $errors->first('position') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('dob')) has-error @endif'>
        {{ Form::label('dob', 'dob',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('dob', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('dob')) <p class="help-block">{{ $errors->first('dob') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('age')) has-error @endif'>
        {{ Form::label('age', 'age',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('age', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('age')) <p class="help-block">{{ $errors->first('age') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('birthplace')) has-error @endif'>
        {{ Form::label('birthplace', 'birthplace',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('birthplace', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('birthplace')) <p class="help-block">{{ $errors->first('birthplace') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('gender')) has-error @endif'>
        {{ Form::label('gender', 'gender',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('gender', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('gender')) <p class="help-block">{{ $errors->first('gender') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('status')) has-error @endif'>
        {{ Form::label('status', 'status',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('status', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('status')) <p class="help-block">{{ $errors->first('status') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('xemployee_status')) has-error @endif'>
        {{ Form::label('xemployee_status', 'xemployee_status',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('xemployee_status', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'12']) }}
            @if ($errors->has('xemployee_status')) <p class="help-block">{{ $errors->first('xemployee_status') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('staff_mode')) has-error @endif'>
        {{ Form::label('staff_mode', 'staff_mode',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('staff_mode', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'10']) }}
            @if ($errors->has('staff_mode')) <p class="help-block">{{ $errors->first('staff_mode') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('print_card_status')) has-error @endif'>
        {{ Form::label('print_card_status', 'print_card_status',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('print_card_status', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'5']) }}
            @if ($errors->has('print_card_status')) <p class="help-block">{{ $errors->first('print_card_status') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('collect_card_status')) has-error @endif'>
        {{ Form::label('collect_card_status', 'collect_card_status',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('collect_card_status', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'5']) }}
            @if ($errors->has('collect_card_status')) <p class="help-block">{{ $errors->first('collect_card_status') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('salary_rev_date')) has-error @endif'>
        {{ Form::label('salary_rev_date', 'salary_rev_date',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('salary_rev_date', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'10']) }}
            @if ($errors->has('salary_rev_date')) <p class="help-block">{{ $errors->first('salary_rev_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('race')) has-error @endif'>
        {{ Form::label('race', 'race',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('race', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('race')) <p class="help-block">{{ $errors->first('race') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('religion')) has-error @endif'>
        {{ Form::label('religion', 'religion',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('religion', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('religion')) <p class="help-block">{{ $errors->first('religion') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('email')) has-error @endif'>
        {{ Form::label('email', 'email',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('email', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('url')) has-error @endif'>
        {{ Form::label('url', 'url',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('url', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('url')) <p class="help-block">{{ $errors->first('url') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('address_aa')) has-error @endif'>
        {{ Form::label('address_aa', 'address_aa',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('address_aa', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('address_aa')) <p class="help-block">{{ $errors->first('address_aa') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('address_ab')) has-error @endif'>
        {{ Form::label('address_ab', 'address_ab',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('address_ab', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('address_ab')) <p class="help-block">{{ $errors->first('address_ab') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('city_a')) has-error @endif'>
        {{ Form::label('city_a', 'city_a',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('city_a', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('city_a')) <p class="help-block">{{ $errors->first('city_a') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('state_a')) has-error @endif'>
        {{ Form::label('state_a', 'state_a',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('state_a', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('state_a')) <p class="help-block">{{ $errors->first('state_a') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('postcode_a')) has-error @endif'>
        {{ Form::label('postcode_a', 'postcode_a',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('postcode_a', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('postcode_a')) <p class="help-block">{{ $errors->first('postcode_a') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('country_a')) has-error @endif'>
        {{ Form::label('country_a', 'country_a',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('country_a', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('country_a')) <p class="help-block">{{ $errors->first('country_a') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('citizenship')) has-error @endif'>
        {{ Form::label('citizenship', 'citizenship',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('citizenship', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('citizenship')) <p class="help-block">{{ $errors->first('citizenship') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('address_ba')) has-error @endif'>
        {{ Form::label('address_ba', 'address_ba',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('address_ba', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('address_ba')) <p class="help-block">{{ $errors->first('address_ba') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('address_bb')) has-error @endif'>
        {{ Form::label('address_bb', 'address_bb',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('address_bb', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('address_bb')) <p class="help-block">{{ $errors->first('address_bb') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('city_b')) has-error @endif'>
        {{ Form::label('city_b', 'city_b',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('city_b', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('city_b')) <p class="help-block">{{ $errors->first('city_b') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('state_b')) has-error @endif'>
        {{ Form::label('state_b', 'state_b',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('state_b', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('state_b')) <p class="help-block">{{ $errors->first('state_b') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('postcode_b')) has-error @endif'>
        {{ Form::label('postcode_b', 'postcode_b',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('postcode_b', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('postcode_b')) <p class="help-block">{{ $errors->first('postcode_b') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('country_b')) has-error @endif'>
        {{ Form::label('country_b', 'country_b',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('country_b', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('country_b')) <p class="help-block">{{ $errors->first('country_b') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('pic_name')) has-error @endif'>
        {{ Form::label('pic_name', 'pic_name',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('pic_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('pic_name')) <p class="help-block">{{ $errors->first('pic_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('mime_type')) has-error @endif'>
        {{ Form::label('mime_type', 'mime_type',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('mime_type', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'30']) }}
            @if ($errors->has('mime_type')) <p class="help-block">{{ $errors->first('mime_type') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('mime_name')) has-error @endif'>
        {{ Form::label('mime_name', 'mime_name',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('mime_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'30']) }}
            @if ($errors->has('mime_name')) <p class="help-block">{{ $errors->first('mime_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('card_id')) has-error @endif'>
        {{ Form::label('card_id', 'card_id',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('card_id', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('card_id')) <p class="help-block">{{ $errors->first('card_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('reason')) has-error @endif'>
        {{ Form::label('reason', 'reason',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('reason', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'10']) }}
            @if ($errors->has('reason')) <p class="help-block">{{ $errors->first('reason') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('pic_contents')) has-error @endif'>
        {{ Form::label('pic_contents', 'pic_contents',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('pic_contents', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'4294967295']) }}
            @if ($errors->has('pic_contents')) <p class="help-block">{{ $errors->first('pic_contents') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('new_inserted_date')) has-error @endif'>
        {{ Form::label('new_inserted_date', 'new_inserted_date',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('new_inserted_date', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('new_inserted_date')) <p class="help-block">{{ $errors->first('new_inserted_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('new_inserted_by')) has-error @endif'>
        {{ Form::label('new_inserted_by', 'new_inserted_by',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('new_inserted_by', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('new_inserted_by')) <p class="help-block">{{ $errors->first('new_inserted_by') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('last_modified_date')) has-error @endif'>
        {{ Form::label('last_modified_date', 'last_modified_date',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('last_modified_date', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('last_modified_date')) <p class="help-block">{{ $errors->first('last_modified_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('last_modified_by')) has-error @endif'>
        {{ Form::label('last_modified_by', 'last_modified_by',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('last_modified_by', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('last_modified_by')) <p class="help-block">{{ $errors->first('last_modified_by') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('data_mig_date')) has-error @endif'>
        {{ Form::label('data_mig_date', 'data_mig_date',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('data_mig_date', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('data_mig_date')) <p class="help-block">{{ $errors->first('data_mig_date') }}</p> @endif
        </div>
    </div>

