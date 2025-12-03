//-- public/js/clinic-users.js --//


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

  // 生年月日入力時に年齢を自動計算
  const birthdayInput = document.getElementById('birthday');
  if (birthdayInput) {
    birthdayInput.addEventListener('change', () => calculateAndFillAge('birthday', 'age'));

    // 既に生年月日が入力されていれば年齢を計算
    if (birthdayInput.value) {
      calculateAndFillAge('birthday', 'age');
    }
  }

});
