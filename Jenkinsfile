pipeline {
  agent {
    dockerfile {
      filename 'Dockerfile'
    }
  }
  stages {
    stage('Build') {
      steps {
        sh 'composer install'
      }
    }
    stage('Test') {
      steps {
        sh 'npm run test'
      }
    }
  }
  options {
    buildDiscarder(logRotator(numToKeepStr: '2'))
  }
}