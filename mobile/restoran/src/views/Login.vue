<template>
  <ion-page>
    <ion-content :fullscreen="true" class="page-background">
      
      <div class="login-wrapper">
        
        <ion-card class="login-card">
          <ion-card-content>
            
            <div class="header-section">
              <ion-icon :icon="personCircleOutline" color="primary" class="logo-icon"></ion-icon>
              <h2>Login</h2>
              <p>Masuk ke akun Anda</p>
            </div>

            <div class="form-inputs">
              <ion-input
                class="custom-input ion-margin-bottom"
                label="Email"
                label-placement="floating"
                fill="outline"
                mode="md"
                type="email"
                v-model="email"
                placeholder="email@anda.com"
              ></ion-input>

              <ion-input
                class="custom-input ion-margin-bottom"
                label="Password"
                label-placement="floating"
                fill="outline"
                mode="md"
                type="password"
                v-model="password"
                @keyup.enter="login"
              ></ion-input>
            </div>

            <div v-if="error" class="error-message ion-margin-bottom">
              <ion-text color="danger">{{ error }}</ion-text>
            </div>

            <ion-button 
              expand="block" 
              class="login-btn" 
              @click="login"
              :disabled="isLoading"
            >
              <span v-if="!isLoading">LOGIN</span>
              <ion-spinner v-else name="dots"></ion-spinner>
            </ion-button>

          </ion-card-content>
        </ion-card>

      </div>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import {
  IonPage,
  IonContent,
  IonCard,
  IonCardContent,
  IonInput,
  IonButton,
  IonText,
  IonIcon,
  IonSpinner
} from '@ionic/vue'
import { personCircleOutline } from 'ionicons/icons'
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()
const email = ref('')
const password = ref('')
const error = ref('')
const isLoading = ref(false)

const login = async () => {
  if (!email.value || !password.value) {
    error.value = 'Email dan Password harus diisi'
    return
  }

  isLoading.value = true
  error.value = ''

  try {
    const res = await axios.post('http://localhost/api_mobile/login.php', {
      email: email.value,
      password: password.value
    })

    if (res.data.status === 'success') {
      localStorage.setItem('user', JSON.stringify(res.data.data))
      router.push('/dashboard')
    } else {
      error.value = res.data.msg
    }
  } catch (err) {
    error.value = 'Gagal terhubung ke server'
  } finally {
    isLoading.value = false
  }
}
</script>

<style scoped>
/* Memberikan sedikit warna background agar kartu menonjol */
.page-background {
  --background: #f4f5f8; 
}

/* Flexbox untuk menaruh konten TEPAT di tengah layar */
.login-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100%;
  padding: 20px;
}

/* Style Kartu Login (Kunci agar terlihat 'kecil') */
.login-card {
  width: 100%;
  max-width: 400px; /* INI KUNCINYA: Lebar maksimal hanya 400px */
  border-radius: 16px; /* Membuat sudut tumpul */
  box-shadow: 0 10px 25px rgba(0,0,0,0.08); /* Bayangan halus */
  background: white;
}

.header-section {
  text-align: center;
  margin-bottom: 25px;
  margin-top: 10px;
}

.logo-icon {
  font-size: 64px;
  margin-bottom: 10px;
}

.header-section h2 {
  font-weight: 700;
  margin: 0;
  color: #333;
}

.header-section p {
  color: #888;
  font-size: 14px;
  margin-top: 5px;
}

.custom-input {
  --border-radius: 8px;
}

.login-btn {
  margin-top: 20px;
  --border-radius: 8px;
  font-weight: 600;
}

.error-message {
  text-align: center;
  font-size: 13px;
  background: #ffebee;
  padding: 8px;
  border-radius: 6px;
}
</style>