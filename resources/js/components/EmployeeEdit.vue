<template>
  <div :dir="currentLanguage === 'ur' ? 'rtl' : 'ltr'" class="container mt-5">
    <div class="language-buttons">
      <button @click="switchLanguage('en')" :class="{ active: currentLanguage === 'en' }">English</button>
      <button @click="switchLanguage('ur')" :class="{ active: currentLanguage === 'ur' }">اردو</button>
    </div>
    
    <h2>{{ translations.edit_employee_detail || 'Edit Employee Detail' }}</h2>
    
    <div v-if="loading">{{ translations.loading || 'Loading...' }}</div>
    <div v-else-if="error" class="alert alert-danger">{{ error }}</div>
    
    <form v-else @submit.prevent="updateEmployee" class="needs-validation">
      <!-- Username -->
      <div class="form-group mb-3">
        <label for="username">{{ translations.username || 'Username' }}</label>
        <input
          type="text"
          id="username"
          v-model="formData.username"
          class="form-control"
          :class="{ 'is-invalid': validationErrors.username }"
          required
        >
        <div v-if="validationErrors.username" class="invalid-feedback">
          {{ getTranslatedError('username', validationErrors.username[0]) }}
        </div>
      </div>

      <!-- Country -->
      <div class="form-group mb-3">
        <label for="country">{{ translations.country || 'Country' }}</label>
        <select
          id="country"
          v-model="formData.country_id"
          class="form-control"
          :class="{ 'is-invalid': validationErrors.country_id }"
          required
          @change="onCountryChange"
        >
          <option value="">{{ translations.select_country || 'Select Country' }}</option>
          <option
            v-for="country in countries"
            :key="country.id"
            :value="country.id"
          >
            {{ country.name }}
          </option>
        </select>
        <div v-if="validationErrors.country_id" class="invalid-feedback">
          {{ getTranslatedError('country', validationErrors.country_id[0]) }}
        </div>
      </div>

      <!-- State -->
      <div class="form-group mb-3">
        <label for="state">{{ translations.state || 'State' }}</label>
        <select
          id="state"
          v-model="formData.state_id"
          class="form-control"
          :class="{ 'is-invalid': validationErrors.state_id }"
          required
          @change="onStateChange"
          :disabled="!formData.country_id"
        >
          <option value="">{{ translations.select_state || 'Select State' }}</option>
          <option
            v-for="state in states"
            :key="state.id"
            :value="state.id"
          >
            {{ state.name }}
          </option>
        </select>
        <div v-if="validationErrors.state_id" class="invalid-feedback">
          {{ getTranslatedError('state', validationErrors.state_id[0]) }}
        </div>
      </div>

      <!-- City -->
      <div class="form-group mb-3">
        <label for="city">{{ translations.city || 'City' }}</label>
        <select
          id="city"
          v-model="formData.city_id"
          class="form-control"
          :class="{ 'is-invalid': validationErrors.city_id }"
          required
          :disabled="!formData.state_id"
        >
          <option value="">{{ translations.select_city || 'Select City' }}</option>
          <option
            v-for="city in cities"
            :key="city.id"
            :value="city.id"
          >
            {{ city.name }}
          </option>
        </select>
        <div v-if="validationErrors.city_id" class="invalid-feedback">
          {{ getTranslatedError('city', validationErrors.city_id[0]) }}
        </div>
      </div>

      <!-- Date of Birth -->
      <div class="form-group mb-3">
        <label for="dob">{{ translations.date_of_birth || 'Date of Birth' }}</label>
        <input
          type="date"
          id="dob"
          v-model="formData.dob"
          class="form-control"
          :class="{ 'is-invalid': validationErrors.dob }"
          required
        >
        <div v-if="validationErrors.dob" class="invalid-feedback">
          {{ getTranslatedError('dob', validationErrors.dob[0]) }}
        </div>
      </div>

      <!-- Income -->
      <div class="form-group mb-3">
        <label for="income">{{ translations.income || 'Income' }}</label>
        <input
          type="number"
          id="income"
          v-model="formData.income"
          class="form-control"
          :class="{ 'is-invalid': validationErrors.income }"
          required
        >
        <div v-if="validationErrors.income" class="invalid-feedback">
          {{ getTranslatedError('income', validationErrors.income[0]) }}
        </div>
      </div>

      <!-- Gender -->
      <div class="form-group mb-3">
        <label for="gender">{{ translations.gender || 'Gender' }}</label>
        <select
          id="gender"
          v-model="formData.gender"
          class="form-control"
          :class="{ 'is-invalid': validationErrors.gender }"
          required
        >
          <option value="male">{{ translations.male || 'Male' }}</option>
          <option value="female">{{ translations.female || 'Female' }}</option>
          <option value="other">{{ translations.other || 'Other' }}</option>
        </select>
        <div v-if="validationErrors.gender" class="invalid-feedback">
          {{ getTranslatedError('gender', validationErrors.gender[0]) }}
        </div>
      </div>

      <!-- Address -->
      <div class="form-group mb-3">
        <label for="address">{{ translations.address || 'Address' }}</label>
        <textarea
          id="address"
          v-model="formData.address"
          class="form-control"
          :class="{ 'is-invalid': validationErrors.address }"
          rows="3"
          required
        ></textarea>
        <div v-if="validationErrors.address" class="invalid-feedback">
          {{ getTranslatedError('address', validationErrors.address[0]) }}
        </div>
      </div>

      <!-- Phone -->
      <div class="form-group mb-3">
        <label for="phone">{{ translations.phone_no || 'Phone Number' }}</label>
        <input
          type="tel"
          id="phone"
          v-model="formData.phone"
          class="form-control"
          :class="{ 'is-invalid': validationErrors.phone }"
          required
        >
        <div v-if="validationErrors.phone" class="invalid-feedback">
          {{ getTranslatedError('phone', validationErrors.phone[0]) }}
        </div>
      </div>

      <button type="submit" class="btn btn-primary" :disabled="loading">
        {{ loading ? translations.updating || 'Updating...' : translations.update || 'Update' }}
      </button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
  name: 'EmployeeEdit',
  props: {
    employeeId: {
      type: [Number, String],
      required: true
    }
  },
  data() {
    return {
      loading: true,
      error: null,
      countries: [],
      states: [],
      cities: [],
      formData: {
        username: '',
        country_id: '',
        state_id: '',
        city_id: '',
        dob: '',
        income: '',
        gender: '',
        address: '',
        phone: ''
      },
      translations: {},
      currentLanguage: 'en',
      validationErrors: {},
      validationMessages: {
        en: {
          required: 'The :field field is required.',
          unique: 'The :field has already been taken.',
          exists: 'The selected :field is invalid.',
          max: {
            numeric: 'The :field may not be greater than :max.',
            string: 'The :field may not be greater than :max characters.'
          },
          min: {
            numeric: 'The :field must be at least :min.',
            string: 'The :field must be at least :min characters.'
          },
          minLength: 'The :field must have at least 3 letters.', // Added this line
          date: 'The :field must be a valid date.',
          numeric: 'The :field must be a number.',
          string: 'The :field must be a string.',
          in: 'The selected :field is invalid.'
        },
        ur: {
          required: ':field درکار ہے۔',
          unique: ':field پہلے سے استعمال میں ہے۔',
          exists: 'منتخب کردہ :field درست نہیں ہے۔',
          max: {
            numeric: ':field :max سے زیادہ نہیں ہو سکتی۔',
            string: ':field :max حروف سے زیادہ نہیں ہو سکتی۔'
          },
          min: {
            numeric: ':field کم از کم :min ہونی چاہیے۔',
            string: ':field کم از کم :min حروف ہونے چاہئیں۔'
          },
          minLength: ':field میں کم از کم 3 حروف ہونے چاہئیں۔', // Added this line
          date: ':field درست تاریخ ہونی چاہیے۔',
          numeric: ':field ایک نمبر ہونا چاہیے۔',
          string: ':field متن ہونا چاہیے۔',
          in: 'منتخب کردہ :field درست نہیں ہے۔'
        }
      },
      fieldTranslations: {
        en: {
          username: 'username',
          income: 'income',
          gender: 'gender',
          address: 'address',
          phone: 'phone number',
          dob: 'date of birth'
        },
        ur: {
          username: 'صارف نام',
          income: 'آمدنی',
          gender: 'جنس',
          address: 'پتہ',
          phone: 'فون نمبر',
          dob: 'تاریخ پیدائش'
        }
      }
  
    };
  },
  async created() {
    await this.fetchTranslations(this.currentLanguage);
    try {
      await Promise.all([
        this.fetchEmployeeData(),
        this.fetchCountries()
      ]);
    } catch (error) {
      console.error('Initialization error:', error);
      this.error = 'Failed to load employee data. Please try refreshing the page.';
    } finally {
      this.loading = false;
    }
  },
  methods: {
    getTranslatedError(field, errorMessage) {
      const lang = this.currentLanguage;
      const fieldName = this.fieldTranslations[lang][field];

      // Determine the validation rule from the error message
      let rule = 'required';
      if (errorMessage.includes('has already been taken')) rule = 'unique';
      if (errorMessage.includes('numeric')) rule = 'numeric';
      if (errorMessage.includes('date')) rule = 'date';
      if (errorMessage.includes('exists')) rule = 'exists';
      if (errorMessage.includes('maximum')) rule = 'max';
      if (errorMessage.includes('minimum')) rule = 'min';
      if (errorMessage.includes('too short')) rule = 'minLength'; // Added this line
      if (errorMessage.includes('in:')) rule = 'in';

      // Get the base translation
      let translation = this.validationMessages[lang][rule];

      // Replace the field name
      translation = translation.replace(':field', fieldName);

      // Handle max/min values
      const maxMatch = errorMessage.match(/maximum is (\d+)/);
      const minMatch = errorMessage.match(/minimum is (\d+)/);
      
      if (maxMatch) {
        translation = this.validationMessages[lang].max.string
          .replace(':field', fieldName)
          .replace(':max', maxMatch[1]);
      }
      if (minMatch) {
        translation = this.validationMessages[lang].min.string
          .replace(':field', fieldName)
          .replace(':min', minMatch[1]);
      }

      // Handle minLength case
      if (errorMessage.includes('too short')) {
        translation = this.validationMessages[lang].minLength
          .replace(':field', fieldName);
      }

      return translation;
    },
    async fetchTranslations(locale) {
  this.loading = true;
  this.error = null;
  try {
    const response = await axios.get(`/translations/${locale}`);
    this.translations = response.data;
    console.log(`Translations loaded for ${locale}:`, this.translations);
  } catch (error) {
    console.error("Error fetching translations:", error);
    this.error = "Failed to load translations.";
  } finally {
    this.loading = false;
  }
},
    async fetchEmployeeData() {
      try {
        const response = await axios.get(`/details/${this.employeeId}`);
        const employee = response.data;
        
        if (!employee) {
          throw new Error('Employee data not found');
        }
        
        this.formData = {
          username: employee.user?.username || '',
          country_id: employee.country?.id || '',
          state_id: employee.state?.id || '',
          city_id: employee.city?.id || '',
          dob: employee.dob || '',
          income: employee.income || '',
          gender: employee.gender || '',
          address: employee.address || '',
          phone: employee.phone || ''
        };

        if (this.formData.country_id) {
          await this.fetchStates(this.formData.country_id);
        }
        if (this.formData.state_id) {
          await this.fetchCities(this.formData.state_id);
        }
      } catch (error) {
        console.error('Error fetching employee:', error);
        throw new Error('Failed to fetch employee data');
      }
    },
    async fetchCountries() {
      try {
        const response = await axios.get('/countries');
        this.countries = response.data || [];
      } catch (error) {
        console.error('Error fetching countries:', error);
        throw new Error('Failed to fetch countries');
      }
    },
    async fetchStates(countryId) {
      try {
        const response = await axios.get(`/states/${countryId}`);
        this.states = response.data || [];
      } catch (error) {
        console.error('Error fetching states:', error);
        this.states = [];
        Swal.fire({
          title: 'Warning',
          text: 'Failed to load states',
          icon: 'warning'
        });
      }
    },
    async fetchCities(stateId) {
      try {
        const response = await axios.get(`/cities/${stateId}`);
        this.cities = response.data || [];
      } catch (error) {
        console.error('Error fetching cities:', error);
        this.cities = [];
        Swal.fire({
          title: 'Warning',
          text: 'Failed to load cities',
          icon: 'warning'
        });
      }
    },
    async onCountryChange() {
      this.formData.state_id = '';
      this.formData.city_id = '';
      this.states = [];
      this.cities = [];
      
      if (this.formData.country_id) {
        await this.fetchStates(this.formData.country_id);
      }
    },
    async onStateChange() {
      this.formData.city_id = '';
      this.cities = [];
      
      if (this.formData.state_id) {
        await this.fetchCities(this.formData.state_id);
      }
    },
    async updateEmployee() {
      this.loading = true;
      this.validationErrors = {}; // Clear previous errors
      
      try {
        await axios.put(`/details/${this.employeeId}`, this.formData);
        
        await Swal.fire({
          title: this.translations.success || 'Success!',
          text: this.translations.employee_updated || 'Employee details updated successfully',
          icon: 'success'
        });
        
        window.location.href = '/detail';
      } catch (error) {
        if (error.response && error.response.status === 422) {
          this.validationErrors = error.response.data.errors;
        } else {
          await Swal.fire({
            title: this.translations.error || 'Error!',
            text: error.response?.data?.message || this.translations.update_failed || 'Failed to update employee details',
            icon: 'error'
          });
        }
      } finally {
        this.loading = false;
      }
    },
    switchLanguage(lang) {
    this.currentLanguage = lang; // Update current language
    localStorage.setItem('preferredLanguage', lang); // Store the selected language in local storage
    this.fetchTranslations(lang);
}
  },

//   mounted() {
//   const storedLanguage = localStorage.getItem('preferredLanguage'); // Default to English
//   this.currentLanguage = storedLanguage;
//   this.fetchTranslations(this.currentLanguage); // Fetch translations for the current language
// }
mounted() {
    // Check local storage for the preferred language
    const storedLanguage = localStorage.getItem('preferredLanguage');
    if (storedLanguage) {
        this.currentLanguage = storedLanguage; // Set the current language from local storage
        console.log(`Loaded language from local storage: ${storedLanguage}`);
        this.fetchTranslations(storedLanguage); // Fetch translations for the stored language
    } else {
        console.log('No preferred language found, defaulting to English.');
        this.fetchTranslations('en'); // Default to English if no preference is set
    }

}



}
</script>

<style scoped>
.is-invalid {
  border-color: #dc3545 !important;
}

.invalid-feedback {
  display: block;
  width: 100%;
  margin-top: 0.25rem;
  font-size: 0.875em;
  color: #dc3545;
}
.container {
  max-width: 800px;
  margin: 20px auto;
  padding: 30px;
  background-color: #ffffff; 
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); 
}

h2 {
  font-size: 1.8rem;
  font-weight: bold;
  text-align: center;
  margin-bottom: 25px;
  color: #333333;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  font-weight: 600;
  margin-bottom: 10px;
  color: #555555;
  font-size: 1rem;
}

.form-control {
  width: 100%;
  padding: 14px 16px; 
  font-size: 1.1rem; 
  border: 1px solid #cccccc;
  border-radius: 8px;
  box-sizing: border-box;
  background-color: #f9f9f9; 
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-control:focus {
  background-color: #ffffff; 
  border-color: #007bff; 
  box-shadow: 0 0 5px rgba(0, 123, 255, 0.25); /* Subtle blue shadow on focus */
}

textarea.form-control {
  resize: vertical; 
  min-height: 100px; 
}

select.form-control {
  background-color: #f9f9f9;
}

.btn-primary {
  display: inline-block;
  padding: 12px 25px; 
  font-size: 1rem;
  font-weight: 600;
  color: #ffffff;
  background-color: #007bff;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  text-align: center;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.btn-primary:hover {
  background-color: #0056b3;
  transform: scale(1.02); 
}

.btn-primary:disabled {
  background-color: #6c757d;
  cursor: not-allowed;
}

.alert {
  padding: 15px;
  font-size: 1rem;
  border-radius: 5px;
  margin-bottom: 20px;
}

.alert-danger {
  background-color: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}

.alert-success {
  background-color: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

@media (max-width: 768px) {
  .container {
    padding: 20px;
  }

  h2 {
    font-size: 1.5rem;
  }

  .btn-primary {
    width: 100%;
  }
}
</style>
