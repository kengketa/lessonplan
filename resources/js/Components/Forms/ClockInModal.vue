<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <TransitionRoot as="template" :show="modelValue">
    <Dialog as="div" class="fixed z-30 inset-0 overflow-y-hidden" @close="close()">
      <div class="flex items-end justify-center mt-80 md:mt-0 text-center sm:block sm:p-0">
        <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0"
                         enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100"
                         leave-to="opacity-0">
          <DialogOverlay class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
        </TransitionChild>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <TransitionChild as="template" enter="ease-out duration-300"
                         enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                         leave-from="opacity-100 translate-y-0 sm:scale-100"
                         leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
          <div
            class="relative inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div>
              <div v-if="clockedIn" class="text-center">
                <div as="h3" class="text-lg leading-6 font-medium text-gray-900">
                  Clocked in
                </div>
                <div class="mt-2 text-3xl text-green-500">
                  <span class="uppercase">{{ clockedIn.clock_in }}</span>
                </div>
              </div>
              <div class="text-center mt-2">
                <div v-if="!clockedIn" as="h3" class="text-lg leading-6 font-medium text-gray-900">
                  Clock in
                </div>
                <div v-if="clockedIn" as="h3" class="text-lg leading-6 font-medium text-gray-900">
                  Clock out
                </div>
                <div class="mt-2 text-4xl font-gray-700 mx-10 xs:mx-20">
                  <span>{{ form.time }}</span>
                </div>
              </div>
            </div>
            <div class="mt-5 w-full flex flex-col md:flex-row md:justify-between">
              <button type="button"
                      class="button button-secondary mb-2 md:mb-0"
                      @click="close()" ref="cancelButtonRef">
                Not now
              </button>
              <button v-if="clockedIn == null && enableClockInButton" type="button" class="button button-primary"
                      @click="submitClockIn()">
                Clock in
              </button>
              <button v-if="clockedIn == null && !enableClockInButton" type="button"
                      class="button text-white opacity-50 cursor-not-allowed"
                      :class="!outDistanceMessage ? 'bg-yellow-500' : 'bg-red-500'"
                      disabled>
                <div class="flex">
                  <svg v-if="!outDistanceMessage" class="animate-spin mr-2 ml-2 h-5 w-5 text-white "
                       xmlns="http://www.w3.org/2000/svg" fill="none"
                       viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <span v-if="outDistanceMessage">{{ outDistanceMessage }}</span>
                  <span v-if="!outDistanceMessage">Detecting location</span>
                </div>
              </button>
              <button v-if="clockedIn != null" type="button" class="button bg-red-500 text-white hover:bg-red-600"
                      @click="submitClockOut()">
                Clock out
              </button>
            </div>
            <div class="absolute top-2 right-2">
              <span v-if="displayCurrentPosition" class="text-2xs text-gray-500">{{ displayCurrentPosition }}</span>
            </div>
          </div>
        </TransitionChild>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script>
import {ref} from 'vue'
import {Dialog, DialogOverlay, DialogTitle, TransitionChild, TransitionRoot} from '@headlessui/vue'
import {CheckIcon} from '@heroicons/vue/outline'
import TextInput from "@/Components/TextInput";
import {useForm} from "@inertiajs/inertia-vue3";
import SearchSelectInput from "@/Components/SearchSelectInput";

export default {
  name: 'ClockInModal',
  components: {
    TextInput,
    Dialog,
    DialogOverlay,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
    CheckIcon,
    SearchSelectInput,
  },
  props: {
    modelValue: {
      type: Boolean,
      default: false
    },
    clockedIn: {
      type: Object,
      default: null
    },
    siteCoordinates: {
      type: Object,
      default: null
    },
  },
  data() {
    return {
      form: useForm({
        time: null
      }),
      interval: null,
      geoLocationOptions: {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
      },
      userCoordinates: {
        lat: null,
        lng: null
      },
      outDistanceMessage: null,
      enableClockInButton: false,
      displayCurrentPosition: "00/00",
      geolocationId: null
    };
  },
  emits: ['update:modelValue'],
  mounted() {
    if (this.clockedIn == null) {
      this.reloadLocation()
    }
  },
  watch: {
    userCoordinates: {
      handler: function () {
        if (!this.siteCoordinates) {
          return;
        }
        let dis = this.haversineDistance(this.userCoordinates, this.siteCoordinates);
        this.displayCurrentPosition = parseInt(dis) + '/' + this.siteCoordinates.radius
        console.log('-----------------');
        console.log(this.displayCurrentPosition);
        console.log('-----------------');
        if (dis <= this.siteCoordinates.radius) {
          this.enableClockInButton = true;
          this.outDistanceMessage = null;
        }
        if (dis > this.siteCoordinates.radius) {
          this.outDistanceMessage = 'Out of the school range'
        }
      },
      deep: true,
    }
  },
  methods: {
    reloadLocation() {
      this.geolocationId = navigator.geolocation.watchPosition(
        position => {
          this.userCoordinates.lat = position.coords.latitude;
          this.userCoordinates.lng = position.coords.longitude;
          this.detectingLocation = false;
        },
        error => {

        },
        this.geoLocationOptions
      );
    },
    submitClockIn() {
      this.form.post(route('dashboard.clock_ins.in'), {
        onSuccess: () => this.$emit('update:modelValue', false),
      });
    },
    submitClockOut() {
      this.form.post(route('dashboard.clock_ins.out'), {
        onSuccess: () => this.$emit('update:modelValue', false),
      });
    },

    close() {
      navigator.geolocation.clearWatch(this.geolocationId);
      this.$emit('update:modelValue', false);
    },
    haversineDistance(pos1, pos2) {
      const R = 3958.8 // Radius of the Earth in miles
      const rlat1 = pos1.lat * (Math.PI / 180) // Convert degrees to radians
      const rlat2 = pos2.lat * (Math.PI / 180) // Convert degrees to radians
      const difflat = rlat2 - rlat1 // Radian difference (latitudes)
      const difflon = (pos2.lng - pos1.lng) * (Math.PI / 180) // Radian difference (longitudes)
      const distance =
        2 *
        R *
        Math.asin(
          Math.sqrt(
            Math.sin(difflat / 2) * Math.sin(difflat / 2) +
            Math.cos(rlat1) *
            Math.cos(rlat2) *
            Math.sin(difflon / 2) *
            Math.sin(difflon / 2)
          )
        )
      let distanceInMeter = distance * 1609.34; // convert mile to meter
      return distanceInMeter;
    }
  },
  beforeDestroy() {
    // prevent memory leak
    clearInterval(this.interval);
    navigator.geolocation.clearWatch(this.geolocationId);
  },
  computed: {},
  created() {
    this.form.time = Intl.DateTimeFormat(navigator.language, {
      hour: 'numeric',
      minute: 'numeric',
      second: 'numeric'
    }).format();
    // update the time every second
    this.interval = setInterval(() => {
      this.form.time = Intl.DateTimeFormat(navigator.language, {
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric'
      }).format()
    }, 1000)
  }
}
</script>
