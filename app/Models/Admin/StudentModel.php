<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class StudentModel extends Model
{
    //
    protected $table = 't_sys_student';
    protected $guarded = [];

    public function setGenderAttribute($value)
    {
        if ($value == '男') {
            $this->attributes['gender'] = 1;
        } else if ($value == '女') {
            $this->attributes['gender'] = 2;
        } else {
            $this->attributes['gender'] = $value ? $value : 1;
        }
    }

    public function setIdTypeAttribute($value)
    {
        if ($value) {
            $code = DictModel::getOne('id_type', $value, false);
            if ($code) {
                $this->attributes['id_type_code'] = $code->code;
            }
            $this->attributes['id_type'] = $value;
        }
    }

    public function setNationAttribute($value)
    {
        if ($value) {
            $code = DictModel::getOne('nation', $value, false);
            if ($code) {
                $this->attributes['nation_code'] = $code->code;
            }
            $this->attributes['nation'] = $value;
        }
    }

    public function setPoliticsStatusAttribute($value)
    {
        if ($value) {
            $code = DictModel::getOne('politics_status', $value, false);
            if ($code) {
                $this->attributes['politics_status_code'] = $code->code;
            }
            $this->attributes['politics_status'] = $value;
        }
    }

    public function setReligionAttribute($value)
    {
        if ($value) {
            $code = DictModel::getOne('religion', $value, false);
            if ($code) {
                $this->attributes['religion_code'] = $code->code;
            }
            $this->attributes['religion'] = $value;
        }
    }

    public function setMaritalStatusAttribute($value)
    {
        if ($value) {
            $code = DictModel::getOne('marital_status', $value, false);
            if ($code) {
                $this->attributes['marital_status_code'] = $code->code;
            }
            $this->attributes['marital_status'] = $value;
        }
    }

    public function setHealthStatusAttribute($value)
    {
        if ($value) {
            $code = DictModel::getOne('health_status', $value, false);
            if ($code) {
                $this->attributes['health_status_code'] = $code->code;
            }
            $this->attributes['health_status'] = $value;
        }
    }

    public function setCountryAttribute($value)
    {
        if ($value) {
            $code = DictModel::getOne('country', $value, false);
            if ($code) {
                $this->attributes['country_code'] = $code->code;
            }
            $this->attributes['country'] = $value;
        }
    }

    public function setGatqAttribute($value)
    {
        if ($value) {
            $code = DictModel::getOne('gatq', $value, false);
            if ($code) {
                $this->attributes['gatq_code'] = $code->code;
            }
            $this->attributes['gatq'] = $value;
        }
    }

    public function setBloodTypeAttribute($value)
    {
        if ($value) {
            $code = DictModel::getOne('blood_type', $value, false);
            if ($code) {
                $this->attributes['blood_type_code'] = $code->code;
            }
            $this->attributes['blood_type'] = $value;
        }
    }

    public function setIdentityTypeAttribute($value)
    {
        if ($value) {
            $code = DictModel::getOne('identity_type', $value, false);
            if ($code) {
                $this->attributes['identity_type_code'] = $code->code;
            }
            $this->attributes['identity_type'] = $value;
        }
    }

    public function setStudentTypeAttribute($value)
    {
        if ($value) {
            $code = DictModel::getOne('student_type', $value, false);
            if ($code) {
                $this->attributes['student_type_code'] = $code->code;
            }
            $this->attributes['student_type'] = $value;
        }
    }

    public function setDeptNameAttribute($value)
    {
        if ($value) {
            $code = DepartmentModel::getone($value, true);
            if ($code) {
                $this->attributes['dept_code'] = $code;
            }
            $this->attributes['dept_name'] = $value;
        }
    }

    public function setCourseNameAttribute($value)
    {
        if ($value) {
            $code = ProfessionalModel::getone($value, true);
            if ($code) {
                $this->attributes['course_code'] = $code;
            }
            $this->attributes['course_name'] = $value;
        }
    }

    public function setClassNameAttribute($value)
    {
        if ($value) {
            $code = ClassModel::getone($value, true);
            if ($code) {
                $this->attributes['class_code'] = $code;
            }
            $this->attributes['class_name'] = $value;
        }
    }

    public function setEducationAttribute($value)
    {
        if ($value) {
            $code = DictModel::getOne('education', $value, false);
            if ($code) {
                $this->attributes['education_code'] = $code->code;
            }
            $this->attributes['education'] = $value;
        }
    }

    public function setInRegistryAttribute($value)
    {
        if ($value == '是') {
            $this->attributes['in_registry'] = 1;
        } else if ($value == '否') {
            $this->attributes['in_registry'] = 0;
        } else {
            $this->attributes['in_registry'] = $value ? $value : 1;
        }
    }

    public function setInSchoolAttribute($value)
    {
        if ($value == '是') {
            $this->attributes['in_school'] = 1;
        } else if ($value == '否') {
            $this->attributes['in_school'] = 0;
        } else {
            $this->attributes['in_school'] = $value ? $value : 1;
        }
    }

    public function setTrainTypeAttribute($value)
    {
        if ($value) {
            $code = DictModel::getOne('train_type', $value, false);
            if ($code) {
                $this->attributes['train_type_code'] = $code->code;
            }
            $this->attributes['train_type'] = $value;
        }
    }

    public function setEntryTypeAttribute($value)
    {
        if ($value) {
            $code = DictModel::getOne('entry_type', $value, false);
            if ($code) {
                $this->attributes['entry_type_code'] = $code->code;
            }
            $this->attributes['entry_type'] = $value;
        }
    }
}
