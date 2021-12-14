<template>
  <Card>
    <Form
      form-class="space-y-8 divide-y divide-gray-200"
      :submit-event="submit"
    >
      <div class="space-y-8 divide-y divide-gray-200 sm:space-y-5">
        <div class="space-y-6 sm:space-y-5">
          <div>
            <h3 v-if="meeting.id == null" class="text-lg leading-6 text-gray-900">
              Create new Meeting
            </h3>
            <h3 v-else class="text-lg leading-6 text-gray-900">
              Update Meeting
            </h3>
          </div>
          <div class="space-y-6 sm:space-y-5">
            <SearchSelectInput
              v-model="form.school_id"
              :error="form.errors.school_id"
              :options="schools"
              :is-show-line="true"
              label="school"
            />
            <TextInput
              v-model="form.title"
              :error="form.errors.title"
              label="Title"
              placeholder="title"
            />
            <DatePickerInput
              v-model="form.date"
              :error="form.errors.date"
              label="Date"
              placeholder="date"
            />
            <TextInput
              v-model="form.time"
              :error="form.errors.time"
              label="Time"
              placeholder="time"
            />
            <TextInput
              v-model="form.location"
              :error="form.errors.location"
              label="Location"
              placeholder="Location"
            />
            <form v-on:submit.prevent>
              <TextInput
                @keyup.enter="addAttendee()"
                v-model="typingAttendee"
                label="attendee"
                placeholder="attendee"
              />
            </form>
            <div class="flex flex-wrap mt-2">
              <div class="bg-yellow-100 px-2 py-1 rounded-md mx-2 my-2 relative"
                   v-for="(attendee,attendeeIndex) in form.attendee" :key="attendeeIndex">
                <span>{{ attendee }}</span>
                <button @click="removeAttendee(attendeeIndex)" type="button"
                        class="absolute -top-2 -right-2 text-red-500">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                       stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
          <hr>
          <p class="text-sm font-medium text-gray-700 mt-4">Agenda</p>
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">
            <div v-for="(agenda,agendaIndex) in form.agendas" :key="agendaIndex"
                 class="border px-4 py-4 relative">
              <div>
                <TextInput
                  v-model="form.agendas[agendaIndex].topic"
                  label="topic"
                  placeholder="topic"
                  :is-show-line="false"
                  :error="form.errors[getErrorKey('agendas.',agendaIndex,'.topic')]"
                />
              </div>
              <div class="my-4">
                <EditorInput label="Agenda detail" height="350" v-model="form.agendas[agendaIndex].detail" />
              </div>
              <div class="my-4">
                <EditorInput label="Decision" height="200" v-model="form.agendas[agendaIndex].decision" />
              </div>
              <button @click="removeAgenda(agendaIndex)" type="button"
                      class="absolute top-2 right-2 text-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </button>
            </div>
            <button v-if="type === 'create'" type="button" @click="addAgenda()"
                    class="border border-dashed h-80 px-4 py-4">
              <div class="flex justify-center items-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Add Agenda</span>
              </div>
              <p v-if="form.errors.agendas && form.agendas.length===0"
                 class="text-sm text-red-600">
                {{ form.errors.agendas }}
              </p>
            </button>
          </div>
        </div>
      </div>
    </Form>
  </Card>
</template>


<script>
import {useForm} from '@inertiajs/inertia-vue3';
import Card from '@/Components/Card';
import TextInput from '@/Components/TextInput';
import TextareaInput from '@/Components/TextareaInput';
import {PlusSmIcon, TrashIcon} from '@heroicons/vue/solid';
import Form from '@/Components/Form';
import SearchSelectInput from "@/Components/SearchSelectInput";
import DatePickerInput from "@/Components/DatePickerInput";
import EditorInput from "../EditorInput";

export default {
  name: 'MeetingForm',
  components: {
    EditorInput,
    Card,
    TextInput,
    TextareaInput,
    PlusSmIcon,
    TrashIcon,
    Form,
    SearchSelectInput,
    DatePickerInput,
  },
  props: {
    type: {
      default: 'create',
      type: String,
    },
    meeting: Object,
    schools: {
      type: Object,
      required: true
    },
    errors: Object,
  },
  data() {
    return {
      form: useForm({
        id: this.meeting.id,
        agendas: this.meeting.agendas ?? [],
        school_id: this.meeting.school_id,
        title: this.meeting.title,
        date: this.meeting.date,
        time: this.meeting.time,
        location: this.meeting.location,
        attendee: this.meeting.attendee ?? []
      }),
      typingAttendee: null
    };
  },
  mounted() {

  },
  methods: {
    getErrorKey(front, index, prop) {
      let key = front + index + prop;
      return key;
    },
    removeAgenda(index) {
      this.form.agendas.splice(index, 1);
    },
    addAttendee() {
      this.form.attendee.push(this.typingAttendee);
      this.typingAttendee = null;
    },
    removeAttendee(index) {
      this.form.attendee.splice(index, 1);
    },
    addAgenda() {
      let newAgenda = {
        id: null,
        topic: "",
        detail: null,
        decision: null
      }
      this.form.agendas.push(newAgenda);
    },
    submit() {
      if (this.type === 'create') {
        this.form.post(route('dashboard.meetings.store'));
      } else {
        this.form.put(route('dashboard.meetings.update', this.meeting.id));
      }
    },
    makeid(length) {
      let result = '';
      const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
      const charactersLength = characters.length;
      for (let i = 0; i < length; i++) {
        result += characters.charAt(
          Math.floor(Math.random() * charactersLength)
        );
      }
      return result;
    },
  },
};
</script>

<style scoped>
</style>

