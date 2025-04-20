<template>
  <div class="p-4" :dir="currentLanguage === 'ur' ? 'rtl' : 'ltr'">
    <div class="language-buttons mb-4 text-center bg-black">
      <button @click="switchLanguage('en')" :class="{ active: currentLanguage === 'en' }" class="px-4">
        English
      </button>
      <button @click="switchLanguage('ur')" :class="{ active: currentLanguage === 'ur' }" class="px-4 py-2">
        اردو
      </button>
    </div>

    <form @submit.prevent="submitDetail">
      <input type="hidden" :value="userId" />
      <input type="hidden" :value="countryId" />
      <input type="hidden" :value="stateId" />
      <input type="hidden" :value="cityId" />

      <div v-if="error" class="alert alert-danger">{{ error }}</div>
      <h2>{{ translations.employee_detail || 'Employee Detail' }}</h2>

      <div class="form-group">
        <label for="dob">{{ translations.dob || 'Date of Birth' }}</label>
        <input type="date" id="dob" v-model="dob" required />
        <span v-if="validationErrors.dob" class="text-red-500 text-sm mt-1">
          {{ getTranslatedError('dob', validationErrors.dob[0]) }}
        </span>
      </div>

      <div class="form-group">
        <label for="income">{{ translations.income || 'Income' }}</label>
        <input
          type="number"
          id="income"
          v-model="income"
          :placeholder="translations.income_placeholder || 'Enter your income'"
          required
        />
        <span v-if="validationErrors.income" class="text-red-500 text-sm mt-1">
          {{ getTranslatedError('income', validationErrors.income[0]) }}
        </span>
      </div>

      <div class="form-group">
        <label for="gender">{{ translations.gender || 'Gender' }}</label>
        <select id="gender" v-model="gender" required>
          <option value="" disabled selected>
            {{ translations.select_gender_placeholder || 'Select your gender' }}
          </option>
          <option value="male">{{ translations.male || 'Male' }}</option>
          <option value="female">{{ translations.female || 'Female' }}</option>
          <option value="other">{{ translations.other || 'Other' }}</option>
        </select>
        <span v-if="validationErrors.gender" class="text-red-500 text-sm mt-1">
          {{ getTranslatedError('gender', validationErrors.gender[0]) }}
        </span>
      </div>

      <div class="form-group">
        <label for="address">{{ translations.address || 'Address' }}</label>
        <textarea
          id="address"
          v-model="address"
          rows="4"
          :placeholder="translations.address_placeholder || 'Enter your address'"
          required
        ></textarea>
        <span v-if="validationErrors.address" class="text-red-500 text-sm mt-1">
          {{ getTranslatedError('address', validationErrors.address[0]) }}
        </span>
      </div>

      <div class="form-group">
        <label for="phone">{{ translations.phone || 'Phone Number' }}</label>
        <input
          type="tel"
          id="phone"
          v-model="phone"
          :placeholder="translations.phone_placeholder || 'Enter your phone number'"
          required
        />
        <span v-if="validationErrors.phone" class="text-red-500 text-sm mt-1">
          {{ getTranslatedError('phone', validationErrors.phone[0]) }}
        </span>
      </div>

      <button type="submit">{{ translations.join_us || 'Join Us' }}</button>
    </form>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
  props: {
    userId: {
      type: Number,
      required: true,
    },
    countryId: {
      type: Number,
      required: true,
    },
    stateId: {
      type: Number,
      required: true,
    },
    cityId: {
      type: Number,
      required: true,
    },
  },
  setup(props) {
    const dob = ref('');
    const income = ref('');
    const gender = ref('');
    const address = ref('');
    const phone = ref('');
    const error = ref('');
    const translations = ref({});
    const currentLanguage = ref('en');
    const validationErrors = ref({});

    // Validation message translations
    const validationMessages = {
      en: {
        required: 'The :field field is required.',
        numeric: 'The :field must be a number.',
        date: 'The :field must be a valid date.',
        exists: 'The selected :field is invalid.',
        max: {
          numeric: 'The :field may not be greater than :max.',
          string: 'The :field may not be greater than :max characters.'
        },
        min: {
          numeric: 'The :field must be at least :min.',
          string: 'The :field must be at least :min characters.'
        },
        in: 'The selected :field is invalid.',
        string: 'The :field must be a string.'
      },
      ur: {
        required: ':field درکار ہے۔',
        numeric: ':field ایک نمبر ہونا چاہیے۔',
        date: ':field درست تاریخ ہونی چاہیے۔',
        exists: 'منتخب کردہ :field درست نہیں ہے۔',
        max: {
          numeric: ':field :max سے زیادہ نہیں ہو سکتی۔',
          string: ':field :max حروف سے زیادہ نہیں ہو سکتی۔'
        },
        min: {
          numeric: ':field کم از کم :min ہونی چاہیے۔',
          string: ':field کم از کم :min حروف ہونے چاہئیں۔'
        },
        in: 'منتخب کردہ :field درست نہیں ہے۔',
        string: ':field متن ہونا چاہیے۔'
      }
    };

    // Field name translations
    const fieldTranslations = {
      en: {
        dob: 'date of birth',
        income: 'income',
        gender: 'gender',
        address: 'address',
        phone: 'phone number'
      },
      ur: {
        dob: 'تاریخ پیدائش',
        income: 'آمدنی',
        gender: 'جنس',
        address: 'پتہ',
        phone: 'فون نمبر'
      }
    };

    const getTranslatedError = (field, errorMessage) => {
      const lang = currentLanguage.value;
      const fieldName = fieldTranslations[lang][field];
      
      // Parse the error message to determine the validation rule
      let rule = 'required'; // default
      if (errorMessage.includes('numeric')) rule = 'numeric';
      if (errorMessage.includes('date')) rule = 'date';
      if (errorMessage.includes('exists')) rule = 'exists';
      if (errorMessage.includes('characters')) rule = 'string';
      if (errorMessage.includes('in:')) rule = 'in';

      // Get the translation for the rule
      let translation = validationMessages[lang][rule];

      // Replace the :field placeholder
      translation = translation.replace(':field', fieldName);

      // Handle max/min values if present
      const maxMatch = errorMessage.match(/maximum is (\d+)/);
      const minMatch = errorMessage.match(/minimum is (\d+)/);
      
      if (maxMatch) {
        translation = validationMessages[lang].max.string.replace(':field', fieldName).replace(':max', maxMatch[1]);
      }
      if (minMatch) {
        translation = validationMessages[lang].min.string.replace(':field', fieldName).replace(':min', minMatch[1]);
      }

      return translation;
    };

    const fetchTranslations = async (locale) => {
      try {
        const response = await axios.get(`/translations/${locale}`);
        translations.value = response.data;
        currentLanguage.value = locale;
      } catch (err) {
        console.error('Error fetching translations:', err);
      }
    };

    const switchLanguage = (lang) => {
      fetchTranslations(lang);
      localStorage.setItem('preferredLanguage', lang);
    };

    const submitDetail = async () => {
      validationErrors.value = {};
      error.value = '';

      try {
        const response = await axios.post('/store-detail', {
          user_id: props.userId,
          country_id: props.countryId,
          state_id: props.stateId,
          city_id: props.cityId,
          dob: dob.value,
          income: income.value,
          gender: gender.value,
          address: address.value,
          phone: phone.value,
        });

        if (response.status === 200) {
          Swal.fire({
            title: translations.value.success_title || 'Success!',
            text: translations.value.success_text || 'Details saved successfully!',
            icon: 'success',
            showCancelButton: true,
            confirmButtonText: translations.value.add_employee_again || 'Add Employee Again',
            cancelButtonText: translations.value.go_to_employee_table || 'Go to Employee Table',
            allowOutsideClick: false,
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = '/register';
            } else if (result.dismiss === Swal.DismissReason.cancel) {
              window.location.href = '/detail';
            }
          });
        }
      } catch (err) {
        if (err.response && err.response.status === 422) {
          validationErrors.value = err.response.data.errors;
        } else {
          error.value = translations.value.error_saving_details || 'Something went wrong. Please try again.';
        }
      }
    };

    onMounted(async () => {
      const storedLanguage = localStorage.getItem('preferredLanguage') || 'en';
      await fetchTranslations(storedLanguage);
    });

    return {
      dob,
      income,
      gender,
      address,
      phone,
      error,
      translations,
      currentLanguage,
      validationErrors,
      submitDetail,
      switchLanguage,
      getTranslatedError
    };
  },
};
</script>

<style scoped>
.form-group {
  margin-bottom: 1.5rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
}

.form-group input,
.form-group select,
.form-group textarea {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.text-red-500 {
  color: #ef4444;
}

.text-sm {
  font-size: 0.875rem;
  line-height: 1.25rem;
}

.mt-1 {
  margin-top: 0.25rem;
}
</style>