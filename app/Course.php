<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Course extends Model
{
    /**
     * Mass assignable fields of Course model
     *
     * @var array
     */
    protected $fillable = [
        'period_id',
        'name',
    ];

    /**
     * Returns current Course id
     *
     * @return int
     */
    public function getId() {
        return $this->attributes['id'];
    }

    /**
     * Returns the name of the Course
     *
     * @return string
     */
    public function getName() {
        return $this->attributes['name'];
    }

    /**
     * Sets the name of the Course
     *
     * @param string $name
     * @return void
     */
    public function setName($name) {
        $this->attributes['name'] = $name;
    }

    /**
     * Returns the Period that owns this Course
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function period() {
        return $this->belongsTo(Period::class);
    }

    /**
     * Returns the Activities belonging to this Course
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activities() {
        return $this->hasMany(Activity::class);
    }

    /**
     * Calculates the required grade in the resting percent for reaching 3.0
     *
     * @return float
     */
    public function howMuchDoINeed() {
        $activities = $this->activities()->get();
        $acum = 0;
        $percentLeft = 100;

        foreach($activities as $activity) {
            $acum = $acum + $activity->getGrade() * $activity->getPercentage();
            $percentLeft = $percentLeft - $activity->getPercentage();
        }

        return (300 - $acum) / $percentLeft;
    }

    /**
     * Calculates the remaining percentage until this course is finished
     *
     * @return float
     */
    public function remainingPercentage() {
        $activities = $this->activities()->get();
        $acum = 0;
        foreach($activities as $activity) {
            $acum = $acum + $activity->getPercentage();
        }
        return 100 - $acum;
    }

    /**
     * Validates period_id and name
     *
     * @param Request $request
     * @return void
     */
    public static function validate(Request $request, $fields = ['period_id', 'name']) {
        $conditions = [
            'period_id' => 'required',
            'name' => 'required',
        ];

        $to_validate = [];
        foreach($fields as $field) {
            $to_validate[$field] = $conditions[$field];
        }

        $request->validate($to_validate);
    }

    /**
     * Event handler for courses
     *
     * @return void
     */
    public static function boot() {
        parent::boot();

        static::deleting(function ($course) {
            $course->activities()->delete();
        });
    }
}
