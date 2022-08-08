pipeline {
    agent any

    stages {
        stage('Build') {
            steps {
                sh 'git ftp init'
            }
        }
        stage('Test') {
            steps {
                echo 'Testing..'
            }
        }
        stage('Deploy') {
            steps {
                echo 'Deploying. revised....'
            }
        }
    }
}