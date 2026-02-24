<template>
  <ion-content class="bg-light">
    
    <div class="header-image-section">
      <img :src="menu.gambar_url" :alt="menu.nama_menu" class="hero-image" @error="handleImageError" />
      
      <ion-button fill="clear" class="floating-back-btn" @click="cancel">
        <div class="icon-bg">
          <ion-icon :icon="closeOutline" class="text-dark"></ion-icon>
        </div>
      </ion-button>
    </div>
    
    <div class="content-section">
      <div class="title-price-row">
        <h1 class="menu-title">{{ menu.nama_menu }}</h1>
        <h3 class="menu-price">Rp {{ formatPrice(menu.harga) }}</h3>
      </div>
      <p class="menu-desc">{{ menu.deskripsi || 'Belum ada deskripsi untuk menu ini.' }}</p>
    </div>

    <div class="control-section">
      <h4 class="section-title">Atur Pesanan</h4>
      
      <div class="qty-container">
        <span class="qty-label">Jumlah Porsi</span>
        <div class="qty-selector">
          <ion-button fill="clear" class="qty-btn" @click="decrement" :disabled="quantity <= 1">
            <ion-icon :icon="removeOutline"></ion-icon>
          </ion-button>
          <span class="qty-number">{{ quantity }}</span>
          <ion-button fill="clear" class="qty-btn add" @click="increment" :disabled="quantity >= (menu.stok || 99)">
            <ion-icon :icon="addOutline"></ion-icon>
          </ion-button>
        </div>
      </div>

      <div class="note-container">
        <ion-label class="note-label">Catatan Tambahan</ion-label>
        <ion-textarea 
          v-model="notes" 
          placeholder="Contoh: Pedas, Tanpa Sayur, dll..." 
          :rows="3"
          class="custom-textarea"
          fill="solid"
        ></ion-textarea>
      </div>
    </div>

  </ion-content>

  <ion-footer class="ion-no-border sticky-footer">
    <div class="footer-content">
      <ion-button expand="block" class="btn-primary" @click="confirm">
        <span class="btn-text">Tambah Pesanan</span>
        <span class="btn-price">Rp {{ formatPrice(totalPrice) }}</span>
      </ion-button>
    </div>
  </ion-footer>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { 
  IonButton, IonContent, IonIcon, IonFooter, 
  IonTextarea, IonLabel, modalController 
} from '@ionic/vue';
import { addOutline, removeOutline, closeOutline } from 'ionicons/icons';

const props = defineProps<{
  menu: any;
}>();

const quantity = ref(1);
const notes = ref('');

const totalPrice = computed(() => {
  return props.menu.harga * quantity.value;
});

const increment = () => {
  if (quantity.value < (props.menu.stok || 99)) quantity.value++;
};

const decrement = () => {
  if (quantity.value > 1) quantity.value--;
};

const formatPrice = (price: number) => {
  return new Intl.NumberFormat('id-ID').format(price);
};

const handleImageError = (e: Event) => {
  (e.target as HTMLImageElement).src = 'https://via.placeholder.com/400x300?text=No+Image';
};

const cancel = () => modalController.dismiss(null, 'cancel');

const confirm = () => {
  modalController.dismiss({
    ...props.menu,
    jumlah: quantity.value,
    catatan: notes.value,
    subtotal: totalPrice.value
  }, 'confirm');
};
</script>

<style scoped>
/* GENERAL */
.bg-light { --background: #f8f9fa; }

/* HEADER & HERO IMAGE */
.header-image-section {
  position: relative;
  width: 100%;
  height: 250px;
  background-color: #eaeaea;
}

.hero-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-bottom-left-radius: 20px;
  border-bottom-right-radius: 20px;
}

.floating-back-btn {
  position: absolute;
  top: 15px;
  left: 10px;
  --padding-start: 0;
  --padding-end: 0;
  height: 40px;
  width: 40px;
}

.icon-bg {
  background: rgba(255, 255, 255, 0.9);
  border-radius: 50%;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.text-dark { color: #333; font-size: 20px; font-weight: bold; }

/* CONTENT INFO */
.content-section {
  padding: 20px;
  background: white;
  border-radius: 20px;
  margin-top: -20px; /* Menarik konten ke atas menimpa border radius gambar */
  position: relative;
  box-shadow: 0 -4px 10px rgba(0,0,0,0.05);
}

.title-price-row {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 10px;
}

.menu-title {
  font-size: 22px;
  font-weight: 800;
  color: #333;
  margin: 0;
  flex: 1;
  line-height: 1.2;
}

.menu-price {
  font-size: 18px;
  font-weight: 800;
  color: #FF8C00;
  margin: 0;
  white-space: nowrap;
  margin-left: 15px;
}

.menu-desc {
  color: #777;
  font-size: 14px;
  line-height: 1.5;
  margin: 0;
}

/* CONTROL SECTION */
.control-section {
  padding: 20px;
  margin-bottom: 80px; 
}

.section-title {
  font-weight: 700;
  color: #333;
  font-size: 16px;
  margin-bottom: 15px;
  margin-top: 0;
}

.qty-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: white;
  padding: 12px 15px;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
  margin-bottom: 20px;
}

.qty-label {
  font-weight: 600;
  color: #555;
  font-size: 15px;
}

.qty-selector {
  display: flex;
  align-items: center;
  background: #f4f4f4;
  border-radius: 20px;
  padding: 2px;
}

.qty-btn {
  --padding-start: 10px;
  --padding-end: 10px;
  margin: 0;
  color: #555;
}

.qty-btn.add {
  color: #FF8C00;
}

.qty-number {
  font-weight: bold;
  font-size: 16px;
  width: 24px;
  text-align: center;
  color: #333;
}

.note-container {
  background: white;
  padding: 15px;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.note-label {
  font-weight: 600;
  color: #555;
  font-size: 14px;
  margin-bottom: 8px;
  display: block;
}

.custom-textarea {
  --background: #f9f9f9;
  --border-radius: 8px;
  --padding-start: 12px;
  --padding-top: 10px;
  font-size: 14px;
  margin-top: 5px;
}

/* FOOTER */
.sticky-footer {
  background: white;
  box-shadow: 0 -4px 15px rgba(0,0,0,0.05);
}

.footer-content {
  padding: 15px 20px;
}

.btn-primary {
  --background: #FF8C00;
  --background-hover: #e07b00;
  --border-radius: 16px;
  height: 55px;
  margin: 0;
  font-weight: bold;
  --box-shadow: 0 4px 10px rgba(255, 140, 0, 0.3);
}

/* Flex layout untuk isi tombol */
.btn-primary::part(native) {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 20px;
}

.btn-text { font-size: 15px; }
.btn-price { font-size: 16px; font-weight: 800; }
</style>