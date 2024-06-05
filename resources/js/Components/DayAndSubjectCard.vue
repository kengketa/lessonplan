<template>
  <Card>
    <div class="w-full grid grid-cols-5 gap-0 text-gray-500">
      <button v-for="(day,index) in workingDays" :key="index" :class="activeDay===day ?'active':''" class="day-button"
              @click="selectDay(day)">
        {{ day }}
      </button>
    </div>
    <div v-if="subjects" class="w-full grid grid-cols-6 gap-0 text-gray-500">
      <button v-for="subject in subjects" :key="subject.index"
              :class="activeSubject && activeSubject.index === subject.index ?'active':''"
              class="subject-button relative"
              @click="selectSubject(subject)">
        <p class="absolute top-0 right-2 text-xs">{{ subject.time }}</p>
        <div>
          <p>{{ subject.code }}</p>
          <p class="text-xs">{{ subject.teacher?.name }}</p>
        </div>
      </button>
    </div>
    <div v-show="adminMode===true" class="flex gap-2 mt-2">
      <button v-for="subject in availableSubjects" :key="subject.id"
              class="border border-gray-200 px-2 py-2 hover:bg-gray-100"
              @click="replaceSubject(subject)">
        <p class="font-bold">{{ subject.code }}</p>
        <small>{{ subject.name }}</small>
      </button>
    </div>
  </Card>
</template>


<script>
import Card from "@/Components/Card.vue";

export default {
  name: "DayAndSubjectCard",
  components: {Card},
  emits: ['update:modelValue'],
  props: {
    grade: {
      type: Object,
      required: true
    },
    adminMode: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      workingDays: ['mon', 'tue', 'wed', 'thu', 'fri'],
      subjects: [
        {index: 0, id: null, code: null, name: null, unit: 0, time: '9.00'},
        {index: 1, id: null, code: null, name: null, unit: 0, time: '10.00'},
        {index: 2, id: null, code: null, name: null, unit: 0, time: '11.00'},
        {index: 3, id: null, code: null, name: null, unit: 0, time: '13.00'},
        {index: 4, id: null, code: null, name: null, unit: 0, time: '14.00'},
        {index: 5, id: null, code: null, name: null, unit: 0, time: '15.00'},
      ],
      activeDay: null,
      activeSubject: null,
      availableSubjects: [],
    }
  },
  mounted() {
    this.activeDay = this.today;
  },
  methods: {
    replaceSubject(subject) {
      const index = this.subjects.findIndex(s => {
        return s.index === this.activeSubject.index
      })
      this.subjects[index].id = subject.id;
      this.subjects[index].code = subject.code;
      this.subjects[index].name = subject.name;
      this.subjects[index].unit = subject.unit;
      this.subjects[index].teacher = subject.teacher;

      this.activeSubject = this.subjects[index];

    },
    autoSelectSubject() {
      const now = new Date();
      const currentTime = now.getHours() + '.' + (now.getMinutes() < 10 ? '0' : '') + now.getMinutes();
      for (const subject of this.subjects) {
        if (currentTime >= subject.time) {
          this.activeSubject = subject;
        }
      }
    },
    selectDay(day) {
      this.activeDay = day;
      this.activeSubject = null;
      this.subjects = this.fetchSubjectFromGradeAndDay();
    },
    selectSubject(subject) {
      this.activeSubject = subject;
    },
    fetchSubjectFromGradeAndDay() {
      // post to db to get subjects for this grade for the active day
      //mock
      return [
        {index: 0, id: 1, code: 'Math', name: 'Mathematics', unit: 1.0, time: '9.00'},
        {index: 1, id: 2, code: 'Eng', name: 'English', unit: 1.5, time: '10.00'},
        {index: 2, id: 3, code: 'Hist', name: 'History', unit: 1.0, time: '11.00'},
        {index: 3, id: 4, code: 'Phys', name: 'Physics', unit: 2.0, time: '13.00'},
        {index: 4, id: 5, code: 'CS', name: 'Computer Science', unit: 1.5, time: '14.00'},
        {index: 5, id: 6, code: 'Bio', name: 'Biology', unit: 1.0, time: '15.00'},
      ];
    }
  },
  watch: {
    activeDay() {
      if (this.adminMode === true) {
        return;
      }
      this.subjects = this.fetchSubjectFromGradeAndDay();
      if (this.activeDay === this.today) {
        this.autoSelectSubject();
      }
    },
    activeSubject() {
      if (this.adminMode === true) {
        return;
      }
      const data = {
        day: this.activeDay,
        subject: this.activeSubject
      }
      this.$emit('update:modelValue', data);
    },
    async adminMode() {
      if (this.adminMode === true) {
        const res = await axios.get(this.route('dashboard.grades.available_subjects', this.grade.id));
        this.availableSubjects = res.data;
        return;
      }
      this.subjects = this.fetchSubjectFromGradeAndDay();
    }
  },
  computed: {
    today() {
      const dayAbbreviations = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
      const currentDate = new Date();
      const dayIndex = currentDate.getDay();
      return dayAbbreviations[dayIndex];
    }
  }
}
</script>

<style scoped>

</style>
