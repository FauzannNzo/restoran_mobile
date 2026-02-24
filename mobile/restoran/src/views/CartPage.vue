<template>
  <ion-page>
    <ion-header class="ion-no-border">
      <ion-toolbar>
        <ion-buttons slot="start">
          <ion-back-button default-href="/home"></ion-back-button>
        </ion-buttons>
        <ion-title>Konfirmasi Pesanan</ion-title>
      </ion-toolbar>
    </ion-header>

    <ion-content class="ion-padding bg-light">

      <div v-if="cartItems.length > 0">
        
        <div class="form-card mb-3">
          <div class="p-3">
            <h6 class="section-title mb-2">Tipe Pesanan</h6>
            <ion-segment v-model="orderType" color="warning" class="custom-segment">
              <ion-segment-button value="dine in">
                <ion-label>Makan di Sini</ion-label>
              </ion-segment-button>
              <ion-segment-button value="take away">
                <ion-label>Bawa Pulang</ion-label>
              </ion-segment-button>
            </ion-segment>
          </div>
        </div>

        <div class="form-card mb-3">
          <ion-list lines="full">
            <ion-item>
              <ion-icon :icon="personOutline" slot="start" color="medium"></ion-icon>
              <ion-label position="stacked">Nama Anda</ion-label>
              <ion-input v-model="customerName" placeholder="Masukkan nama pemesan"></ion-input>
            </ion-item>

            <ion-item v-if="orderType === 'dine in'">
              <ion-icon :icon="restaurantOutline" slot="start" color="medium"></ion-icon>
              <ion-label position="stacked">Pilih Meja</ion-label>
              <ion-select 
                placeholder="Pilih Nomor Meja" 
                interface="action-sheet"
                @ionChange="handleTableChange($event)"
              >
                <ion-select-option v-for="meja in tables" :key="meja.id" :value="meja.no_meja">
                  Meja {{ meja.no_meja }} (Kapasitas: {{ meja.kapasitas || 'Standard' }})
                </ion-select-option>
              </ion-select>
            </ion-item>

            <ion-item>
              <ion-icon :icon="walletOutline" slot="start" color="medium"></ion-icon>
              <ion-label position="stacked">Metode Pembayaran</ion-label>
              <ion-select v-model="paymentMethod" interface="action-sheet">
                <ion-select-option value="cash">Tunai (Bayar di Kasir)</ion-select-option>
                <ion-select-option value="qris">QRIS (E-Wallet/M-Banking)</ion-select-option>
              </ion-select>
            </ion-item>
          </ion-list>
        </div>

        <h6 class="section-title">Detail Pesanan:</h6>
        <ion-list class="cart-list">
          <ion-item v-for="(item, index) in cartItems" :key="index" lines="none" class="cart-item">
            <ion-thumbnail slot="start">
              <img :src="item.gambar_url" @error="handleImageError" />
            </ion-thumbnail>
            
            <ion-label>
              <h2>{{ item.nama_menu }}</h2>
              <p class="text-sm">Rp {{ formatPrice(item.harga) }}</p>
              
              <div class="qty-control mt-1">
                <ion-button fill="outline" size="small" color="medium" class="qty-btn" @click="decrementQty(index)">
                  <ion-icon :icon="removeOutline"></ion-icon>
                </ion-button>
                <span class="qty-number">{{ item.jumlah }}</span>
                <ion-button fill="outline" size="small" color="warning" class="qty-btn" @click="incrementQty(index)">
                  <ion-icon :icon="addOutline"></ion-icon>
                </ion-button>
              </div>

              <p v-if="item.catatan && item.catatan !== '-'" class="text-xs text-danger mt-1">
                Catatan: {{ item.catatan }}
              </p>
            </ion-label>

            <div slot="end" class="text-right">
              <p class="price mb-1">Rp {{ formatPrice(item.subtotal || item.harga) }}</p>
              <ion-button fill="clear" color="danger" size="small" @click="removeItem(index)">
                <ion-icon :icon="trashOutline"></ion-icon>
              </ion-button>
            </div>
          </ion-item>
        </ion-list>

        <div class="checkout-section">
          <div class="total-row">
            <span>Total Bayar:</span>
            <h3>Rp {{ formatPrice(totalPrice) }}</h3>
          </div>
          
          <ion-button 
            expand="block" 
            class="btn-orange" 
            @click="processCheckout"
            :disabled="isProcessing"
          >
            <span v-if="!isProcessing">Kirim Pesanan</span>
            <ion-spinner v-else name="dots"></ion-spinner>
          </ion-button>
        </div>

      </div> 
      
      <div v-else class="empty-state">
        <ion-icon :icon="cartOutline" class="empty-icon"></ion-icon>
        <p>Keranjang kamu masih kosong.</p>
        <ion-button router-link="/home" fill="outline" shape="round" color="warning">
          Pesan Makan Dulu
        </ion-button>
      </div>

    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { 
  IonPage, IonHeader, IonToolbar, IonTitle, IonContent, 
  IonButtons, IonBackButton, IonList, IonItem, IonLabel, 
  IonInput, IonThumbnail, IonButton, IonIcon, IonSpinner, 
  alertController, IonSelect, IonSelectOption,
  IonSegment, IonSegmentButton
} from '@ionic/vue';
import { trashOutline, personOutline, restaurantOutline, cartOutline, walletOutline, addOutline, removeOutline } from 'ionicons/icons';

const router = useRouter();
const API_URL_CHECKOUT = 'http://127.0.0.1:8000/api/checkout';
const API_URL_MEJA = 'http://127.0.0.1:8000/api/mejas';

const cartItems = ref<any[]>([]);
const tables = ref<any[]>([]);
const customerName = ref('');
const tableNumber = ref('');
const orderType = ref('dine in');
const paymentMethod = ref('cash');
const isProcessing = ref(false);

onMounted(() => {
  loadCart();
  fetchTables();
});

const loadCart = () => {
  const savedCart = localStorage.getItem('myCart');
  if (savedCart) {
    cartItems.value = JSON.parse(savedCart);
  }
};

const fetchTables = async () => {
  try {
    const response = await axios.get(API_URL_MEJA);
    if(response.data.success) {
      tables.value = response.data.data;
    }
  } catch (error) {
    console.error("Gagal ambil meja:", error);
  }
};

const incrementQty = (index: number) => {
  // Tambah jumlah
  cartItems.value[index].jumlah++;
  // Update subtotal (harga * jumlah baru)
  cartItems.value[index].subtotal = cartItems.value[index].harga * cartItems.value[index].jumlah;
  // Simpan ke local storage
  saveCart();
};

const decrementQty = (index: number) => {
  if (cartItems.value[index].jumlah > 1) {
    // Kurangi jumlah
    cartItems.value[index].jumlah--;
    // Update subtotal
    cartItems.value[index].subtotal = cartItems.value[index].harga * cartItems.value[index].jumlah;
    // Simpan ke local storage
    saveCart();
  } else {
    // Kalau jumlahnya 1 dan dikurangi lagi, langsung hapus dari keranjang
    removeItem(index);
  }
};

const saveCart = () => {
  localStorage.setItem('myCart', JSON.stringify(cartItems.value));
};

// --- LOGIC CHECKOUT ---
const handleTableChange = (event: any) => {
  tableNumber.value = event.detail.value;
};

const processCheckout = async () => {
  if (!customerName.value.trim()) {
    showAlert('Data Kurang', 'Mohon isi nama Anda agar tidak tertukar.');
    return;
  }
  
  if (orderType.value === 'dine in' && !tableNumber.value) {
    showAlert('Data Kurang', 'Untuk makan di tempat, mohon pilih nomor meja.');
    return;
  }

  isProcessing.value = true;

  try {
    const payload = {
      nama_konsumen: customerName.value,
      no_meja: orderType.value === 'dine in' ? tableNumber.value : null,
      metode_pesanan: orderType.value,
      metode_pembayaran: paymentMethod.value,
      total_bayar: totalPrice.value,
      items: cartItems.value
    };

    const response = await axios.post(API_URL_CHECKOUT, payload);

    if (response.data.success) {
      localStorage.removeItem('myCart');
      cartItems.value = [];
      router.push('/success');
    }

  } catch (error: any) {
    console.error("Error Checkout:", error);
    const msg = error.response?.data?.message || 'Gagal mengirim pesanan.';
    showAlert('Gagal', msg);
  } finally {
    isProcessing.value = false;
  }
};

// --- UTILS ---
const totalPrice = computed(() => {
  return cartItems.value.reduce((sum, item) => sum + (item.subtotal || item.harga), 0);
});

const removeItem = (index: number) => {
  cartItems.value.splice(index, 1);
  saveCart();
};

const formatPrice = (price: number) => {
  return new Intl.NumberFormat('id-ID').format(price);
};

const handleImageError = (e: Event) => {
  (e.target as HTMLImageElement).src = 'https://via.placeholder.com/150?text=No+Image';
};

const showAlert = async (header: string, message: string) => {
  const alert = await alertController.create({ header, message, buttons: ['OK'] });
  await alert.present();
};
</script>

<style scoped>
.bg-light { --background: #f8f9fa; }
.mb-2 { margin-bottom: 8px; }
.mb-3 { margin-bottom: 15px; }
.mt-1 { margin-top: 5px; }
.p-3 { padding: 15px; }

.form-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
  overflow: hidden;
}

.section-title {
  margin: 0 0 10px 5px;
  font-weight: 700;
  color: #333;
  font-size: 14px;
}

.custom-segment {
  --background: #f4f4f4;
  border-radius: 8px;
  padding: 4px;
}
.custom-segment ion-segment-button {
  --border-radius: 6px;
  --color-checked: #fff;
  --indicator-color: #FF8C00;
  font-weight: bold;
}

.cart-item {
  background: white;
  border-radius: 12px;
  margin-bottom: 10px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.05);
}

.qty-control {
  display: flex;
  align-items: center;
  gap: 8px;
}
.qty-btn {
  --padding-start: 4px;
  --padding-end: 4px;
  height: 24px;
  width: 24px;
  margin: 0;
  --border-radius: 6px;
}
.qty-number {
  font-weight: bold;
  font-size: 14px;
  width: 20px;
  text-align: center;
}

.price { color: #FF8C00; font-weight: bold; font-size: 14px; }
.text-sm { font-size: 12px; color: #666; margin: 2px 0; }
.text-xs { font-size: 11px; }
.text-danger { color: #dc3545; }

.empty-state { text-align: center; margin-top: 60px; color: #999; }
.empty-icon { font-size: 64px; margin-bottom: 10px; color: #ddd; }

.checkout-section {
  margin-top: 20px;
  border-top: 2px dashed #eee;
  padding-top: 20px;
  padding-bottom: 50px;
}

.total-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
  font-size: 16px;
  font-weight: bold;
}
.total-row h3 { margin: 0; color: #FF8C00; font-weight: 800;}

.btn-orange {
  --background: #FF8C00;
  --background-hover: #e07b00;
  --border-radius: 12px;
  font-weight: bold;
  height: 45px;
}
</style>