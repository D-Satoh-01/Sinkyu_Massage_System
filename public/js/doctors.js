//-- public/js/di-registration.js --//


// 郵便番号から住所を検索する関数
async function searchAddress() {
  await searchAndFillAddress('postal_code', {
    combined: false,
    address1: 'address_1',
    address2: 'address_2'
  });
}

// ページ読み込み時に実行
document.addEventListener('DOMContentLoaded', function() {
  // 郵便番号入力時の処理
  setupPostalCodeInput('postal_code', searchAddress);
});

// 医療機関選択時の新規登録フィールドの制御
function updateMedicalInstitutionFields() {
  const select = document.getElementById('medical_institutions_id');
  const newName = document.getElementById('new_medical_institution_name');

  if (select.value === '') {
    // 非選択の場合、入力フォームを有効化
    newName.readOnly = false;
  } else {
    // 選択されている場合、入力フォームを無効化してクリア
    newName.readOnly = true;
    newName.value = '';
  }
}

// 新規医療機関名入力時の医療機関選択の制御
function clearMedicalInstitutionSelect() {
  const select = document.getElementById('medical_institutions_id');
  const newName = document.getElementById('new_medical_institution_name');

  if (newName.value.trim() !== '') {
    // 入力がある場合、選択を初期値（－－－）に戻す
    select.value = '';
  }
}
