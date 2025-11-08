//-- public/js/doctors.js --//


// 郵便番号から住所を検索する関数
async function searchAddress() {
  await searchAndFillAddress('postal_code', {
    combined: false,
    address1: 'address_1',
    address2: 'address_2'
  });
}

// 医療機関選択時の新規登録フィールドの制御
function updateMedicalInstitutionFields() {
  toggleSelectAndNewFields('medical_institutions_id', ['new_medical_institution_name']);
}

// 新規医療機関名入力時の医療機関選択の制御
function clearMedicalInstitutionSelect() {
  clearSelectOnNewFieldInput('new_medical_institution_name', 'medical_institutions_id');
}

// ページ読み込み時に実行
document.addEventListener('DOMContentLoaded', function() {
  // 郵便番号入力時の処理
  setupPostalCodeInput('postal_code', searchAddress);
});
