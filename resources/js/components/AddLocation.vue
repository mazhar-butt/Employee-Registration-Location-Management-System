<template>
      <button class="px-4 py-2 mb-2 bg-blue-500 text-white font-medium rounded-lg shadow hover:bg-blue-600"

       @click="navigateTo('detail')" >{{ translations.back || 'Back' }}</button>

  <div :dir="currentLanguage === 'ur' ? 'rtl' : 'ltr'" class="container mx-auto p-8 max-w-4xl bg-white shadow-md rounded-lg">
    <div class="language-buttons mb-4 text-center">
      <button @click="switchLanguage('en')" :class="{ active: currentLanguage === 'en' }" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
        English
      </button>
      <button @click="switchLanguage('ur')" :class="{ active: currentLanguage === 'ur' }" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
        اردو
      </button>
    </div>

    <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">{{ translations.add_location_details || 'Add Location Details' }}</h2>
    
    <form @submit.prevent="saveLocations" class="space-y-6">
      <!-- Country Input -->
      <div>
        <label class="block text-lg font-medium text-gray-700 mb-2">{{ translations.country || 'Country' }}</label>
        <input 
          v-model="formData.country" 
          type="text" 
          class="w-full p-3 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          :placeholder="translations.enter_country_name || 'Enter country name'"
          required
        >
      </div>

      <!-- States Container -->
      <div v-if="formData.country" class="space-y-4">
        <div v-for="(state, stateIndex) in formData.states" :key="stateIndex" class="bg-gray-50 border border-gray-200 p-4 rounded-lg shadow">
          <!-- State Input -->
          <div class="flex items-center gap-4 mb-4">
            <input 
              v-model="state.name" 
              type="text" 
              class="flex-1 p-3 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              :placeholder="translations.enter_state_name || 'Enter state name'"
              required
            >
            <button 
              type="button" 
              @click="addCity(stateIndex)" 
              class="px-4 py-2 bg-blue-500 text-white font-medium rounded-lg shadow hover:bg-blue-600"
              :disabled="state.cities.length >= 5"
            >
              {{ translations.add_city || 'Add City' }}
            </button>
            <button 
              type="button" 
              @click="removeState(stateIndex)" 
              class="px-4 py-2 bg-red-500 text-white font-medium rounded-lg shadow hover:bg-red-600"
            >
              {{ translations.remove_state || 'Remove State' }}
            </button>
          </div>

          <!-- Cities Container -->
          <div class="ml-8 space-y-2">
            <div v-for="(city, cityIndex) in state.cities" :key="cityIndex" class="flex items-center gap-4">
              <input 
                v-model="city.name" 
                type="text" 
                class="flex-1 p-3 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                :placeholder="translations.enter_city_name || 'Enter city name'"
                required
              >
              <button 
                type="button" 
                @click="removeCity(stateIndex, cityIndex)" 
                class="px-4 py-2 bg-red-500 text-white font-medium rounded-lg shadow hover:bg-red-600"
              >
                {{ translations.remove_city || 'Remove City' }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Add State Button -->
      <div v-if="formData.country" class="flex items-center gap-4">
        <button 
          type="button" 
          @click="addState" 
          class="px-6 py-2 bg-green-500 text-white font-medium rounded-lg shadow hover:bg-green-600"
          :disabled="formData.states.length >= 5"
        >
          {{ translations.add_state || 'Add State' }}
        </button>
      </div>

      <!-- Save All Button -->
      <div v-if="canSaveAll" class="mt-6 text-center">
        <button 
          type="submit" 
          class="px-8 py-3 bg-blue-600 text-white text-lg font-semibold rounded-lg shadow hover:bg-blue-700"
        >
          {{ translations.save_all || 'Save All' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
  name: 'LocationForm',
  
  data() {
    return {
      formData: {
        country: '',
        states: []
      },
      translations: {},
      currentLanguage: 'en'
    }
  },

  computed: {
    // Determines if the Save All button should be shown
    canSaveAll() {
      return this.formData.states.some(state => state.cities.length > 0);
    }
  },

  methods: {
    async fetchTranslations(locale) {
      try {
        const response = await axios.get(`/translations/${locale}`);
        this.translations = response.data;
        this.currentLanguage = locale; // Update current language
      } catch (error) {
        console.error("Error fetching translations:", error);
      }
    },

    navigateTo(routeName) {
      try {
        this.$router.push({ name: routeName });
      } catch (error) {
        console.error("Navigation error:", error);
        window.location.href = `/${routeName}`;
      }
    },

    switchLanguage(lang) {
        this.currentLanguage = lang; // Update current language
        localStorage.setItem('preferredLanguage', lang); // Store the selected language in local storage
        this.fetchTranslations(lang);
    },

    addState() {
      if (this.formData.states.length < 5) {
        this.formData.states.push({
          name: '',
          cities: []
        });
      }
    },

    removeState(stateIndex) {
      this.formData.states.splice(stateIndex, 1);
    },

    addCity(stateIndex) {
      if (this.formData.states[stateIndex].cities.length < 5) {
        this.formData.states[stateIndex].cities.push({
          name: ''
        });
      }
    },

    removeCity(stateIndex, cityIndex) {
      this.formData.states[stateIndex].cities.splice(cityIndex, 1);
    },

    async saveLocations() {
      try {
        const response = await axios.post('/location', {
            ...this.formData,
            locale: this.currentLanguage // Add the current language to the request
        });
        // Show success Sweet Alert
        Swal.fire({
          icon: 'success',
          title: this.translations.success || 'Success!',
          text: this.translations.locations_added || 'Locations added successfully',
          confirmButtonText: this.translations.ok_button_text || 'OK'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = '/detail';
          }
        });

        this.resetForm();
      } catch (error) {
        console.error('Save error:', error);
        // Show error Sweet Alert
        let errorMessage = this.translations.error_saving || 'Error saving locations';
        if (error.response) {
          errorMessage = error.response.data.message || errorMessage;
          if (error.response.data.errors) {
            errorMessage += ': ' + Object.values(error.response.data.errors).flat().join(', ');
          }
        } else if (error.request) {
          errorMessage = this.translations.no_response || 'No response from server';
        } else {
          errorMessage = error.message;
        }

        Swal.fire({
          icon: 'error',
          title: this.translations.oops || 'Oops...',
          text: errorMessage
        });
      }
    },

    resetForm() {
      this.formData = {
        country: '',
        states: []
      };
    }
  },

  mounted() {
    const storedLanguage = localStorage.getItem('preferredLanguage');
    if (storedLanguage) {
        this.currentLanguage = storedLanguage; // Set the current language from local storage
        this.fetchTranslations(storedLanguage); // Fetch translations for the stored language
    } else {
        this.fetchTranslations('en'); // Default to English if no preference is set
    }
  }
}
</script>
