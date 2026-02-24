<template>
  <ion-page>
    <ion-content class="ion-padding success-bg" :fullscreen="true">
      <div class="success-container">
        <div class="icon-wrapper">
          <ion-icon :icon="checkmarkCircle" class="success-icon"></ion-icon>
        </div>

        <h1 class="title">Pesanan Berhasil!</h1>
        <p class="subtitle">
          Terima kasih! Pesanan kamu sudah masuk ke dapur dan sedang menunggu untuk diproses.
        </p>

        <div class="info-card">
          <div class="info-item">
            <ion-icon :icon="restaurantOutline" class="info-icon"></ion-icon>
            <span>Dapur sedang bersiap...</span>
          </div>
          <div class="info-item">
            <ion-icon :icon="walletOutline" class="info-icon"></ion-icon>
            <span>Jangan lupa siapkan pembayaran.</span>
          </div>
        </div>

        <div class="action-buttons">
          <ion-button expand="block" class="btn-primary mb-2" @click="backToHome">
            Kembali ke Beranda
          </ion-button>
        </div>
      </div>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { IonPage, IonContent, IonIcon, IonButton } from '@ionic/vue';
import { checkmarkCircle, restaurantOutline, walletOutline } from 'ionicons/icons';
import { useRouter } from 'vue-router';

const router = useRouter();

const backToHome = () => {
  // Kembali ke home dan bersihkan history supaya tidak bisa di-back ke halaman sukses
  router.replace('/home');
};
</script>

<style scoped>
.success-bg {
  --background: #ffffff;
}

.success-container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  height: 100%;
  text-align: center;
  padding: 20px;
}

.icon-wrapper {
  background: #e6f9ed;
  border-radius: 50%;
  padding: 20px;
  margin-bottom: 30px;
  animation: popIn 0.5s ease-out forwards;
}

.success-icon {
  font-size: 100px;
  color: #28a745;
}

.title {
  font-weight: 800;
  color: #333;
  margin-bottom: 10px;
  font-size: 26px;
}

.subtitle {
  color: #666;
  font-size: 14px;
  line-height: 1.6;
  margin-bottom: 30px;
  padding: 0 10px;
}

.info-card {
  background: #f8f9fa;
  border-radius: 12px;
  padding: 15px;
  width: 100%;
  margin-bottom: 40px;
  border: 1px dashed #ccc;
}

.info-item {
  display: flex;
  align-items: center;
  gap: 10px;
  color: #555;
  font-size: 14px;
  font-weight: 600;
  margin-bottom: 10px;
}
.info-item:last-child {
  margin-bottom: 0;
}
.info-icon {
  color: #FF8C00;
  font-size: 20px;
}

.action-buttons {
  width: 100%;
}

.btn-primary {
  --background: #FF8C00;
  --background-hover: #e07b00;
  --border-radius: 12px;
  font-weight: bold;
  height: 50px;
}
.mb-2 {
  margin-bottom: 15px;
}

/* Animasi Muncul Ikon */
@keyframes popIn {
  0% { transform: scale(0.5); opacity: 0; }
  80% { transform: scale(1.1); opacity: 1; }
  100% { transform: scale(1); opacity: 1; }
}
</style>